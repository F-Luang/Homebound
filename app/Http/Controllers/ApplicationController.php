<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\AdoptionCheckin;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\ApplicationSubmitted;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        if (auth()->user()->isAdmin()) {
            $applications = Application::with(['user', 'pet', 'pet.images', 'meetGreet'])
                ->whereHas('pet')
                ->whereHas('user')
                ->when(
                    $request->search,
                    fn($q) =>
                    $q->whereHas(
                        'user',
                        fn($q) =>
                        $q->where('name', 'like', "%{$request->search}%")
                            ->orWhere('email', 'like', "%{$request->search}%")
                    )->orWhereHas(
                            'pet',
                            fn($q) =>
                            $q->where('name', 'like', "%{$request->search}%")
                        )
                )
                ->when(
                    $request->status,
                    fn($q) =>
                    $q->where('status', $request->status)
                )
                ->when(
                    $request->species,
                    fn($q) =>
                    $q->whereHas(
                        'pet',
                        fn($q) =>
                        $q->where('species', $request->species)
                    )
                )
                ->latest('submitted_at')
                ->paginate(20);
        } else {
            $applications = Application::with(['pet', 'pet.images', 'meetGreet'])
                ->whereHas('pet')
                ->where('user_id', auth()->id())
                ->latest('submitted_at')
                ->paginate(20);
        }

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pet_id'                  => 'required|exists:pets,id',
            'home_type'               => 'required|in:apartment,house,condo,other',
            'has_yard'                => 'boolean',
            'has_other_pets'          => 'boolean',
            'other_pets_description'  => 'nullable|string|max:255',
            'has_children'            => 'boolean',
            'children_ages'           => 'nullable|string|max:100',
            'experience'              => 'required|in:first_time,some,experienced',
            'hours_alone'             => 'nullable|integer|min:0|max:24',
            'reason'                  => 'required|string|max:1000',
            'notes'                   => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate open applications — friendly redirect instead of crash
        $exists = Application::where('user_id', auth()->id())
            ->where('pet_id', $request->pet_id)
            ->whereNotIn('status', ['rejected', 'completed', 'cancelled'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['application' => 'You already have an open application for this pet.']);
        }

        $application = Application::create([
            'user_id'                => auth()->id(),
            'pet_id'                 => $request->pet_id,
            'home_type'              => $request->home_type,
            'has_yard'               => $request->boolean('has_yard'),
            'has_other_pets'         => $request->boolean('has_other_pets'),
            'other_pets_description' => $request->other_pets_description,
            'has_children'           => $request->boolean('has_children'),
            'children_ages'          => $request->children_ages,
            'experience'             => $request->experience,
            'hours_alone'            => $request->hours_alone,
            'reason'                 => $request->reason,
            'notes'                  => $request->notes,
            'status'                 => 'pending',
            'submitted_at'           => now(),
        ]);

        $application->load('pet');

        // Send confirmation email — don't crash if mail fails
        try {
            Mail::to($request->user())->send(new ApplicationSubmitted($application));
        } catch (\Exception $e) {
            // Mail failed silently — application is still submitted
        }

        return redirect()->route('applications.index')->with('success', 'Application submitted!');
    }

    // Admin only
    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $transitions = [
            'pending' => ['under_review', 'rejected'],
            'under_review' => ['meet_greet', 'rejected'],
            'meet_greet' => ['home_check', 'rejected'],
            'home_check' => ['approved', 'rejected'],
            'approved' => ['completed'],
        ];

        $allowed = $transitions[$application->status] ?? [];

        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', $allowed)],
            'notes'  => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $application) {
            $application->update([
                'status' => $request->status,
                'notes'  => $request->notes ?? $application->notes,
            ]);

            // When approved — lock pet and reject all competing open applications
            if ($request->status === 'approved') {
                $application->pet->update(['status' => 'pending']);

                Application::where('pet_id', $application->pet_id)
                    ->where('id', '!=', $application->id)
                    ->whereNotIn('status', ['rejected', 'completed', 'cancelled'])
                    ->update(['status' => 'rejected']);
            }

            // When completed — mark pet as adopted + schedule follow-up check-ins
            if ($request->status === 'completed') {
                $application->pet->update(['status' => 'adopted']);

                $today = now();
                foreach ([
                    ['1-week check-in',  $today->copy()->addWeek()],
                    ['1-month check-in', $today->copy()->addMonth()],
                    ['6-month check-in', $today->copy()->addMonths(6)],
                ] as [$label, $due]) {
                    AdoptionCheckin::create([
                        'application_id' => $application->id,
                        'label'          => $label,
                        'due_at'         => $due->toDateString(),
                        'status'         => 'pending',
                    ]);
                }
            }
        });

        // Send status update email outside transaction — don't crash if mail fails
        try {
            Mail::to($application->user)->send(new ApplicationStatusUpdated($application));
        } catch (\Exception $e) {
            // Mail failed silently — status update already persisted
        }

        return back()->with('success', 'Application updated.');
    }

    public function cancel(Application $application)
    {
        if ($application->user_id !== auth()->id())
            abort(403);

        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be cancelled.');
        }

        $application->update(['status' => 'cancelled']);
        return back()->with('success', 'Application cancelled.');
    }

    public function show(Application $application): View
    {
        if (auth()->user()->role === 'adopter' && $application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->load(['user', 'pet.images', 'meetGreet.volunteer']);

        $transitions = [
            'pending' => ['under_review', 'rejected'],
            'under_review' => ['meet_greet', 'rejected'],
            'meet_greet' => ['home_check', 'rejected'],
            'home_check' => ['approved', 'rejected'],
            'approved' => ['completed'],
        ];

        $nextStages = $transitions[$application->status] ?? [];

        return view('applications.show', compact('application', 'nextStages'));
    }
}
