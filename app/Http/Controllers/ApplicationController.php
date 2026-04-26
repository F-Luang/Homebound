<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        // Admins see all; adopters see only their own
        $applications = auth()->user()->isAdmin()
            ? Application::with(['user', 'pet'])->latest('submitted_at')->paginate(20)
            : Application::with('pet')->where('user_id', auth()->id())->latest('submitted_at')->paginate(20);

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

        Application::create([
            'user_id' => auth()->id(),
            'pet_id' => $request->pet_id,
            'notes' => $request->notes,
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

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
}