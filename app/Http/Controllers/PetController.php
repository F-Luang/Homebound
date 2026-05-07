<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PetController extends Controller
{
    // Public listing — all roles + guests can browse
    public function index(Request $request): View
    {
        $pets = Pet::with('images')
            ->whereIn('status', ['available', 'pending']) // ← exclude adopted
            ->when($request->species, fn($q) => $q->where('species', $request->species))
            ->when($request->search, fn($q) => $q->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('breed', 'like', "%{$request->search}%");
            }))
            ->when($request->size, fn($q) => $q->where('size', $request->size))
            ->when($request->activity_level, fn($q) => $q->where('activity_level', $request->activity_level))
            ->orderByRaw("FIELD(status, 'available', 'pending')")
            ->latest()
            ->paginate(12);

        return view('pets.index', compact('pets'));
    }

    public function show(Pet $pet): View
    {
        $pet->load([
            'images',                                                    // ← all images for gallery
            'primaryImage',                                              // ← main display photo
            'medicalRecords' => fn($q) => $q->latest('record_date'),
        ]);
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
            'weight_kg' => 'nullable|numeric|min:0',
            'food' => 'nullable|string|max:255',
            'feeding_time' => 'nullable|string|max:255',
            'water' => 'nullable|string|max:255',
            'medication' => 'nullable|string|max:255',
            'vet' => 'nullable|string|max:255',
            'special_note' => 'nullable|string|max:255',
        ]);

        $pet = Pet::create($validated);

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $result = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $image->getRealPath(),
                    [
                        'folder' => 'homebound/pets',
                        'public_id' => 'pet_' . $pet->id . '_' . time() . '_' . $index,
                    ]
                );

                $pet->images()->create([
                    'path' => $result->getSecurePath(),
                    'is_primary' => $index === 0,
                ]);
            }
        }
        return redirect()->route('pets.show', $pet)->with('success', 'Pet added successfully.');
    }

    public function edit(Pet $pet): View
    {
        $pet->load('images'); // ← add this
        return view('pets.edit', compact('pet'));
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
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
            'weight_kg' => 'nullable|numeric|min:0.01',
            'food' => 'nullable|string|max:255',
            'feeding_time' => 'nullable|string|max:255',
            'water' => 'nullable|string|max:255',
            'medication' => 'nullable|string|max:255',
            'vet' => 'nullable|string|max:255',
            'special_note' => 'nullable|string|max:255',
            'status' => 'nullable|in:available,pending,adopted',
        ]);

        $pet->update($validated);

        // ADD new photos — never delete existing ones here
        // Deletion is handled individually by PetImageController@destroy
        if ($request->hasFile('images')) {
            $hasPrimary = $pet->images()->where('is_primary', true)->exists();

            foreach ($request->file('images') as $i => $file) {
                $result = \CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary::upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'homebound/pets',
                        'public_id' => 'pet_' . $pet->id . '_' . time() . '_' . $i,
                    ]
                );

                $pet->images()->create([
                    'path' => $result->getSecurePath(),
                    'is_primary' => !$hasPrimary && $i === 0,
                ]);

                $hasPrimary = true;
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