<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Pet;
use App\Models\Application;
use App\Models\MeetGreet;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard.index', [
            'availableCount' => Pet::where('status', 'available')->count(),
            'pendingCount' => Application::where('status', 'pending')->count(),
            'adoptedThisMonth' => Pet::where('status', 'adopted')->whereMonth('updated_at', now()->month)->count(),
            'scheduledMeetGreets' => MeetGreet::where('status', 'scheduled')->count(),
            'recentApplications' => Application::with(['user', 'pet'])
                ->whereHas('pet')
                ->whereHas('user')
                ->latest('submitted_at')
                ->take(5)
                ->get(),
            'recentPets' => Pet::latest()->take(5)->get(),
        ]);
    }
}