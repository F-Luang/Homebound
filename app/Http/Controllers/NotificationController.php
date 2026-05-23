<?php
namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MeetGreet;
use App\Models\Surrender;
use App\Models\AdoptionCheckin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $tab  = $request->get('tab', 'applications');

        if ($user->role === 'admin') {

            // Badge counts (always loaded, lightweight)
            $counts = [
                'applications' => Application::count(),
                'visits'       => MeetGreet::count(),
                'checkins'     => AdoptionCheckin::whereIn('status', ['pending', 'missed'])->count(),
                'surrenders'   => Surrender::count(),
            ];

            // Only load the active tab's data, paginated
            $applications = $tab === 'applications'
                ? Application::with(['pet', 'user'])->latest('submitted_at')->paginate(15)->withQueryString()
                : null;

            $visits = $tab === 'visits'
                ? MeetGreet::with(['application.pet', 'application.user'])->latest()->paginate(15)->withQueryString()
                : null;

            $checkins = $tab === 'checkins'
                ? AdoptionCheckin::with(['application.pet', 'application.user'])
                    ->whereIn('status', ['pending', 'missed'])
                    ->orderBy('due_at')
                    ->paginate(15)
                    ->withQueryString()
                : null;

            $surrenders = $tab === 'surrenders'
                ? Surrender::latest()->paginate(15)->withQueryString()
                : null;

        } else {

            $counts = [
                'applications' => Application::where('user_id', $user->id)->count(),
                'visits'       => MeetGreet::whereHas('application', fn($q) => $q->where('user_id', $user->id))->count(),
                'checkins'     => AdoptionCheckin::whereHas('application', fn($q) => $q->where('user_id', $user->id))
                                    ->whereIn('status', ['pending', 'missed'])->count(),
                'surrenders'   => 0,
            ];

            $applications = $tab === 'applications'
                ? Application::with('pet')
                    ->where('user_id', $user->id)
                    ->latest('submitted_at')
                    ->paginate(15)
                    ->withQueryString()
                : null;

            $visits = $tab === 'visits'
                ? MeetGreet::with(['application.pet'])
                    ->whereHas('application', fn($q) => $q->where('user_id', $user->id))
                    ->latest()
                    ->paginate(15)
                    ->withQueryString()
                : null;

            $checkins = $tab === 'checkins'
                ? AdoptionCheckin::with(['application.pet'])
                    ->whereHas('application', fn($q) => $q->where('user_id', $user->id))
                    ->orderBy('due_at')
                    ->paginate(15)
                    ->withQueryString()
                : null;

            $surrenders = null;
        }

        return view('notifications.index', compact(
            'tab', 'counts', 'applications', 'visits', 'checkins', 'surrenders'
        ));
    }
}
