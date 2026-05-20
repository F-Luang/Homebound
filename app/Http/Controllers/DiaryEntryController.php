<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\DiaryEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DiaryEntryController extends Controller
{
    public function store(Request $request, Pet $pet): RedirectResponse
    {
        $request->validate([
            'content' => 'required|string|max:500',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $publicId = 'homebound/diary/pet_' . $pet->id . '_' . time();
            Storage::disk('cloudinary')->putFileAs('', $request->file('image'), $publicId);
            $imagePath = Storage::disk('cloudinary')->url($publicId);
        }

        DiaryEntry::create([
            'pet_id'    => $pet->id,
            'posted_by' => auth()->id(),
            'content'   => $request->content,
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Diary entry posted!');
    }

    public function destroy(DiaryEntry $entry): RedirectResponse
    {
        if ($entry->image_path && str_contains($entry->image_path, 'cloudinary.com')) {
            try {
                preg_match('/homebound\/diary\/[^.]+/', $entry->image_path, $matches);
                if ($matches) Storage::disk('cloudinary')->delete($matches[0]);
            } catch (\Exception $e) {}
        }

        $entry->delete();
        return back()->with('success', 'Entry removed.');
    }
}
