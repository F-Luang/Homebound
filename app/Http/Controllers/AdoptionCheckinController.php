<?php

namespace App\Http\Controllers;

use App\Models\AdoptionCheckin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdoptionCheckinController extends Controller
{
    public function index(): View
    {
        $checkins = AdoptionCheckin::with(['application.pet', 'application.user', 'completedBy'])
            ->orderByRaw("CASE status WHEN 'pending' THEN 0 WHEN 'missed' THEN 1 ELSE 2 END")
            ->orderBy('due_at')
            ->paginate(20);

        return view('checkins.index', compact('checkins'));
    }

    public function complete(Request $request, AdoptionCheckin $checkin): RedirectResponse
    {
        $request->validate(['notes' => 'nullable|string|max:1000']);

        $checkin->update([
            'status'       => 'completed',
            'notes'        => $request->notes,
            'completed_by' => auth()->id(),
            'completed_at' => now(),
        ]);

        return back()->with('success', "Check-in marked as completed.");
    }
}
