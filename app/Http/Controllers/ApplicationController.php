<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\ApplicationSubmitted;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function index(Request $request): View
    {
        if (auth()->user()->isAdmin()) {
            $applications = Application::with(['user', 'pet'])
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
            $applications = Application::with('pet')
                ->where('user_id', auth()->id())
                ->latest('submitted_at')
                ->paginate(20);
        }

        return view('applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Prevent duplicate open applications
        $exists = Application::where('user_id', auth()->id())
            ->where('pet_id', $request->pet_id)
            ->whereNotIn('status', ['rejected', 'completed'])
            ->exists();

        abort_if($exists, 409, 'You already have an open application for this pet.');

        $application = Application::create([   // ← assign to $application
            'user_id' => auth()->id(),
            'pet_id' => $request->pet_id,
            'notes' => $request->notes,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        // Load pet relationship before sending email
        $application->load('pet');

        // Send confirmation email to adopter
        Mail::to($request->user())->send(new ApplicationSubmitted($application));

        return redirect()->route('applications.index')->with('success', 'Application submitted!');
    }

    // Admin only
    public function updateStatus(Request $request, Application $application): RedirectResponse
    {
        $request->validate(['status' => 'required|string', 'notes' => 'nullable|string']);

        // Enforce valid transitions — no skipping stages
        $transitions = [
            'pending' => ['under_review', 'rejected'],
            'under_review' => ['meet_greet', 'rejected'],
            'meet_greet' => ['home_check', 'rejected'],
            'home_check' => ['approved', 'rejected'],
            'approved' => ['completed'],
        ];

        $allowed = $transitions[$application->status] ?? [];
        abort_unless(in_array($request->status, $allowed), 422, 'Invalid status transition.');

        $application->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $application->notes,
        ]);
        // Send status update email to adopter
        Mail::to($application->user)->send(new ApplicationStatusUpdated($application));

        // When approved — lock pet and reject all competing applications
        if ($request->status === 'approved') {
            $application->pet->update(['status' => 'pending']);

            Application::where('pet_id', $application->pet_id)
                ->where('id', '!=', $application->id)
                ->whereNotIn('status', ['rejected', 'completed'])
                ->update(['status' => 'rejected']);
        }

        // When completed — mark pet as adopted
        if ($request->status === 'completed') {
            $application->pet->update(['status' => 'adopted']);
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
        $application->delete();
        return back()->with('success', 'Application cancelled.');
    }
    public function show(Application $application): View
    {
        // Adopters can only see their own
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