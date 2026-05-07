<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PetImageController extends Controller
{
    public function store(Request $request, Pet $pet): RedirectResponse
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $hasPrimary = $pet->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $i => $file) {
            // Upload to Cloudinary
            $result = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'homebound/pets',
                'public_id' => 'pet_' . $pet->id . '_' . time() . '_' . $i,
            ]);

            $pet->images()->create([
                'path' => $result->getSecurePath(), // full https URL
                'is_primary' => !$hasPrimary && $i === 0,
            ]);

            $hasPrimary = true;
        }

        return back()->with('success', 'Photos uploaded successfully.');
    }

    public function setPrimary(PetImage $image): RedirectResponse
    {
        $image->pet->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary photo updated.');
    }

    public function destroy(PetImage $image): RedirectResponse
    {
        // Delete from Cloudinary if it's a Cloudinary URL
        if (str_contains($image->path, 'cloudinary.com')) {
            try {
                // Extract public_id from URL
                preg_match('/homebound\/pets\/[^.]+/', $image->path, $matches);
                if ($matches) {
                    Cloudinary::destroy($matches[0]);
                }
            } catch (\Exception $e) {
                // Continue even if Cloudinary delete fails
            }
        }

        $image->delete();

        return back()->with('success', 'Photo removed.');
    }
}