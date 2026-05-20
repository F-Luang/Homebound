<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use App\Models\PetFavourite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavouriteController extends Controller
{
    public function index(): View
    {
        $favourites = auth()->user()
            ->favourites()
            ->with(['pet', 'pet.images'])
            ->latest('created_at')
            ->get()
            ->filter(fn($f) => $f->pet !== null); // exclude deleted pets

        return view('favourites.index', compact('favourites'));
    }

    public function store(Pet $pet): RedirectResponse
    {
        // Idempotent — ignore duplicates
        PetFavourite::firstOrCreate([
            'user_id' => auth()->id(),
            'pet_id'  => $pet->id,
        ]);

        return back()->with('success', "{$pet->name} added to your saved pets.");
    }

    public function destroy(Pet $pet): RedirectResponse
    {
        PetFavourite::where('user_id', auth()->id())
            ->where('pet_id', $pet->id)
            ->delete();

        return back()->with('success', "{$pet->name} removed from your saved pets.");
    }
}
