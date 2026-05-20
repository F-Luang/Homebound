<?php

namespace App\Http\Controllers;

use App\Models\SuccessStory;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class SuccessStoryController extends Controller
{
    // Public wall — published stories only
    public function index(): View
    {
        $stories = SuccessStory::with('user')
            ->where('is_published', true)
            ->latest()
            ->paginate(12);

        $pendingCount = auth()->check() && auth()->user()->isAdmin()
            ? SuccessStory::where('is_published', false)->count()
            : 0;

        return view('stories.index', compact('stories', 'pendingCount'));
    }

    // Adopter story submission form
    public function create(): View
    {
        // Only adopters with a completed application can submit
        $completedApplications = Application::with('pet')
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->whereDoesntHave('successStory')
            ->get();

        return view('stories.create', compact('completedApplications'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'content'        => 'required|string|min:30|max:1000',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3048',
        ]);

        $application = Application::findOrFail($request->application_id);

        // Ensure the application belongs to this adopter and is completed
        abort_unless(
            $application->user_id === auth()->id() && $application->status === 'completed',
            403
        );

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $publicId = 'homebound/stories/story_' . auth()->id() . '_' . time();
            Storage::disk('cloudinary')->putFileAs('', $request->file('photo'), $publicId);
            $photoPath = Storage::disk('cloudinary')->url($publicId);
        }

        SuccessStory::create([
            'application_id' => $application->id,
            'user_id'        => auth()->id(),
            'pet_name'       => $application->pet->name,
            'content'        => $request->content,
            'photo_path'     => $photoPath,
            'is_published'   => false,
        ]);

        return redirect()->route('stories.index')
            ->with('success', 'Your story has been submitted and will appear after review. Thank you! 🐾');
    }

    // Admin — approve
    public function approve(SuccessStory $story): RedirectResponse
    {
        $story->update(['is_published' => true]);
        return back()->with('success', 'Story published.');
    }

    // Admin — delete
    public function destroy(SuccessStory $story): RedirectResponse
    {
        if ($story->photo_path && str_contains($story->photo_path, 'cloudinary.com')) {
            try {
                preg_match('/homebound\/stories\/[^.]+/', $story->photo_path, $matches);
                if ($matches) Storage::disk('cloudinary')->delete($matches[0]);
            } catch (\Exception $e) {}
        }
        $story->delete();
        return back()->with('success', 'Story removed.');
    }
}
