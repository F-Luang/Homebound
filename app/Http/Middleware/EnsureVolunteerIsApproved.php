<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureVolunteerIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->isPendingApproval()) {
            // Allow logout route so they're not trapped
            if ($request->routeIs('logout')) {
                return $next($request);
            }
            return redirect()->route('volunteer.pending');
        }

        return $next($request);
    }
}