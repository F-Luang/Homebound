<?php
namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\MeetGreet;
use App\Models\Surrender;
use App\Models\AdoptionCheckin;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $applications = Application::with(['pet', 'user'])->latest('submitted_at')->take(20)->get();
            $visits       = MeetGreet::with(['application.pet', 'application.user'])->latest()->take(10)->get();
            $surrenders   = Surrender::latest()->take(15)->get();
            $checkins     = AdoptionCheckin::with(['application.pet', 'application.user'])
                                ->whereIn('status', ['pending', 'missed'])
                                ->orderBy('due_at')
                                ->take(15)
                                ->get();
        } else {
            $applications = Application::with('pet')
                ->where('user_id', $user->id)
                ->latest('submitted_at')
                ->get();
            $visits = MeetGreet::with(['application.pet'])
                ->whereHas('application', fn($q) => $q->where('user_id', $user->id))
                ->latest()
                ->get();
            $surrenders = collect();
            $checkins   = AdoptionCheckin::with(['application.pet'])
                ->whereHas('application', fn($q) => $q->where('user_id', $user->id))
                ->orderBy('due_at')
                ->get();
        }

        return view('notifications.index', compact('applications', 'visits', 'surrenders', 'checkins'));
    }
}
