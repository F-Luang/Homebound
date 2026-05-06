<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class VolunteerController extends Controller
{
    public function index(): View
    {
        $pending = User::where('role', 'volunteer')->where('is_approved', false)->latest()->get();
        $approved = User::where('role', 'volunteer')->where('is_approved', true)->latest()->get();

        return view('volunteers.index', compact('pending', 'approved'));
    }

    public function approve(User $user): RedirectResponse
    {
        abort_unless($user->role === 'volunteer', 403);
        $user->update(['is_approved' => true]);
        return back()->with('success', "{$user->name} has been approved as a volunteer.");
    }

    public function revoke(User $user): RedirectResponse
    {
        abort_unless($user->role === 'volunteer', 403);
        $user->update(['is_approved' => false]);
        return back()->with('success', "{$user->name}'s volunteer access has been revoked.");
    }
}