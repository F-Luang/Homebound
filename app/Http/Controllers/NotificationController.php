<?php
namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\MeetGreet;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $applications = Application::with(['pet', 'user'])->latest()->take(20)->get();
            $visits = MeetGreet::with(['application.pet', 'application.user'])->latest()->take(10)->get();
        } else {
            $applications = Application::with('pet')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
            $visits = MeetGreet::with(['application.pet', 'application.user'])
                ->whereHas('application', fn($q) => $q->where('user_id', $user->id))
                ->latest()
                ->get();
        }
        return view('notifications.index', compact('applications', 'visits'));
    }
}