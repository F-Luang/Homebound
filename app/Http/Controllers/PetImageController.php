<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class PetImageController extends Controller
{
    // Upload additional photos to an existing pet
    public function store(Request $request, Pet $pet): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $hasPrimary = $pet->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $i => $file) {
            $path = $file->store('pets', 'public');
            $pet->images()->create([
                'path' => $path,
                'is_primary' => !$hasPrimary && $i === 0,
            ]);
            $hasPrimary = true;
        }

        return back()->with('success', 'Photos uploaded successfully.');
    }

    // Set a photo as the primary display image
    public function setPrimary(Pet $pet, PetImage $image): RedirectResponse
    {
        // Remove current primary
        $pet->images()->update(['is_primary' => false]);

        // Set new primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary photo updated.');
    }

    // Delete a single photo
    public function destroy(Pet $pet, PetImage $image): RedirectResponse
    {
        // Delete file from storage
        Storage::disk('public')->delete($image->path);

        // If deleting primary, promote next image
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $next = $pet->images()->first();
            $next?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Photo deleted.');
    }
}