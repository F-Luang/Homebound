<?php

namespace App\Http\Controllers;

use App\Models\MeetGreet;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MeetGreetController extends Controller
{
    public function index(): View
    {
        // 1. Fetch the Meet & Greets (Existing Logic)
        $meetGreets = auth()->user()->isAdmin()
            ? MeetGreet::with(['application.pet', 'application.user', 'volunteer'])->latest('scheduled_at')->get()
            : MeetGreet::with(['application.pet', 'application.user'])
                ->where('volunteer_id', auth()->id())
                ->latest('scheduled_at')
                ->get();

        // 2. Fetch applications ready for scheduling (New Logic)
        $openApplications = Application::with(['user', 'pet'])
            ->where('status', 'meet_greet')
            ->get();

        // 3. Pass both to the view
        return view('meet-greets.index', compact('meetGreets', 'openApplications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
        ]);

        // Only one meet-greet per application
        $exists = MeetGreet::where('application_id', $request->application_id)
            ->where('status', 'scheduled')
            ->exists();

        abort_if($exists, 409, 'A meet & greet is already scheduled for this application.');

        MeetGreet::create([
            'application_id' => $request->application_id,
            'volunteer_id' => auth()->id(),
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        return back()->with('success', 'Meet & greet scheduled.');
    }

    public function updateStatus(Request $request, MeetGreet $meetGreet): RedirectResponse
    {
        $request->validate(['status' => 'required|in:completed,cancelled']);

        $meetGreet->update(['status' => $request->status]);

        return back()->with('success', 'Meet & greet updated.');
    }
}