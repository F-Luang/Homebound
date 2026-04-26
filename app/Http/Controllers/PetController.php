<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PetController extends Controller
{
    // Public listing — all roles + guests can browse
    public function index(Request $request): View
    {
        $pets = Pet::with('images')  // changed from 'primaryImage'
            ->where('status', 'available')
            ->when($request->species, fn($q) => $q->where('species', $request->species))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->latest()
            ->paginate(12);

        return view('pets.index', compact('pets'));
    }

    public function show(Pet $pet): View
    {
        $pet->load(['images', 'medicalRecords' => fn($q) => $q->latest('record_date')]);
        return view('pets.show', compact('pet'));
    }

    // Admin only — guarded in routes
    public function create(): View
    {
        return view('pets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'breed' => 'nullable|string',
            'age_months' => 'required|integer|min:0',
            'size' => 'required|string',
            'activity_level' => 'required|string',
            'bio' => 'nullable|string',
            'good_with_kids' => 'boolean',
            'hypoallergenic' => 'boolean',
            'is_senior' => 'boolean',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $pet = Pet::create($validated);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('pets', 'public');

                $pet->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0, // first image = primary
                ]);
            }
        }

        return redirect()->route('pets.show', $pet)->with('success', 'Pet added successfully.');
    }

    public function edit(Pet $pet): View
    {
        return view('pets.create', compact('pet'));
    }

    public function update(Request $request, Pet $pet): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'species' => 'required|string',
            'breed' => 'nullable|string',
            'age_months' => 'required|integer|min:0',
            'size' => 'required|string',
            'activity_level' => 'required|string',
            'bio' => 'nullable|string',
            'good_with_kids' => 'boolean',
            'hypoallergenic' => 'boolean',
            'is_senior' => 'boolean',
            'images.*' => 'nullable|image|max:2048',
        ]);

        $pet->update($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('pets', 'public');

                $pet->images()->create([
                    'path' => $path,
                    'is_primary' => $pet->images()->count() === 0 && $index === 0,
                ]);
            }
        }

        return redirect()->route('pets.show', $pet)->with('success', 'Pet updated successfully.');
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        $pet->delete();
        return redirect()->route('pets.index')->with('success', 'Pet removed.');
    }
}