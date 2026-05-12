<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->except('avatar'));

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it's a Cloudinary URL
            if ($user->avatar && str_contains($user->avatar, 'cloudinary.com')) {
                try {
                    preg_match('/homebound\/avatars\/[^.]+/', $user->avatar, $matches);
                    if ($matches)
                        Storage::disk('cloudinary')->delete($matches[0]);
                } catch (\Exception $e) {
                }
            }

            $publicId = 'homebound/avatars/user_' . $user->id . '_' . time();
            Storage::disk('cloudinary')->putFileAs('', $request->file('avatar'), $publicId);
            $user->avatar = Storage::disk('cloudinary')->url($publicId);
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
