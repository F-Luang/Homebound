<?php

namespace App\Http\Controllers;

use App\Services\PetMatchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MatchController extends Controller
{
    // GET /match — show the form with no results yet
    public function index(): View
    {
        return view('match.index');
    }

    // POST /match — process preferences and return ranked results
    public function run(Request $request, PetMatchService $matchService): View
    {
        $request->validate([
            'species' => 'nullable|string',
            'hypoallergenic' => 'nullable|boolean',
            'activity' => 'nullable|string',
            'size' => 'nullable|string',
            'weights.activity' => 'nullable|integer|min:0|max:5',
            'weights.kids' => 'nullable|integer|min:0|max:5',
            'weights.size' => 'nullable|integer|min:0|max:5',
            'weights.senior' => 'nullable|integer|min:0|max:5',
        ]);

        // Build the prefs array the service expects
        $prefs = [
            'species' => $request->input('species', 'any'),
            'hypoallergenic' => (bool) $request->input('hypoallergenic', false),
            'activity' => $request->input('activity', 'moderate'),
            'size' => $request->input('size', 'medium'),
            'weights' => [
                'activity' => (int) $request->input('weights.activity', 4),
                'kids' => (int) $request->input('weights.kids', 3),
                'size' => (int) $request->input('weights.size', 2),
                'senior' => (int) $request->input('weights.senior', 1),
            ],
        ];

        $results = $matchService->match($prefs);

        return view('match.index', compact('results'));
    }
}