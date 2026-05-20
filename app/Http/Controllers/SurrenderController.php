<?php

namespace App\Http\Controllers;

use App\Models\Surrender;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurrenderController extends Controller
{
    public function create(): View
    {
        return view('surrenders.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'submitter_name'    => 'required|string|max:100',
            'submitter_email'   => 'required|email|max:150',
            'submitter_phone'   => 'nullable|string|max:30',
            'pet_name'          => 'required|string|max:80',
            'species'           => 'required|in:dog,cat,rabbit,bird,hamster,other',
            'breed'             => 'nullable|string|max:80',
            'age_years'         => 'nullable|integer|min:0|max:30',
            'urgency'           => 'required|in:low,medium,high',
            'reason'            => 'required|string|min:20|max:1000',
            'health_notes'      => 'nullable|string|max:500',
            'behavioral_notes'  => 'nullable|string|max:500',
        ]);

        Surrender::create($request->only([
            'submitter_name', 'submitter_email', 'submitter_phone',
            'pet_name', 'species', 'breed', 'age_years',
            'urgency', 'reason', 'health_notes', 'behavioral_notes',
        ]));

        return redirect()->route('surrenders.create')
            ->with('success', 'Your surrender request has been received. Our team will contact you within 48 hours.');
    }

    // Admin only
    public function index(): View
    {
        $surrenders = Surrender::latest()->paginate(20);
        return view('surrenders.index', compact('surrenders'));
    }

    public function updateStatus(Request $request, Surrender $surrender): RedirectResponse
    {
        $request->validate([
            'status'      => 'required|in:pending,contacted,accepted,declined',
            'admin_notes' => 'nullable|string|max:500',
        ]);

        $surrender->update([
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Surrender request updated.');
    }
}
