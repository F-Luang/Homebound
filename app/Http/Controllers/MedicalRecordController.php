<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MedicalRecordController extends Controller
{
    public function index(Pet $pet): View
    {
        // Volunteers and admins can view; adopters cannot
        abort_if(auth()->user()->role === 'adopter', 403);

        $records = $pet->medicalRecords()->with('recordedBy')->get();
        return view('medical.index', compact('pet', 'records'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'record_type' => 'required|in:vaccination,medication,checkup,surgery,other',
            'record_date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string|max:2000',
        ]);

        MedicalRecord::create([
            ...$validated,
            'recorded_by' => auth()->id(),
        ]);

        return back()->with('success', 'Record saved.');
    }

    public function destroy(MedicalRecord $medicalRecord): RedirectResponse
    {
        $medicalRecord->delete();
        return back()->with('success', 'Record deleted.');
    }
}