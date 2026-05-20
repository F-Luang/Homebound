<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MeetGreetController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PetImageController;
use App\Http\Controllers\VolunteerController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\AdoptionCheckinController;
use App\Http\Controllers\SuccessStoryController;
use App\Http\Controllers\SurrenderController;
use App\Http\Controllers\DiaryEntryController;

// -------------------------------------------------------
// Admin only — MUST come before public pet routes
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('pets', [PetController::class, 'store'])->name('pets.store');
    Route::get('pets/{pet}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('pets/{pet}', [PetController::class, 'update'])->name('pets.update');
    Route::patch('pets/{pet}', [PetController::class, 'update']);
    Route::delete('pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.updateStatus');

    Route::post('/medical', [MedicalRecordController::class, 'store'])->name('medical.store');
    Route::delete('/medical/{medicalRecord}', [MedicalRecordController::class, 'destroy'])->name('medical.destroy');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    Route::post('pets/{pet}/images', [PetImageController::class, 'store'])->name('pet-images.store');
    Route::patch('pets/{pet}/images/{image}/primary', [PetImageController::class, 'setPrimary'])->name('pet-images.setPrimary');
    Route::delete('pets/{pet}/images/{image}', [PetImageController::class, 'destroy'])->name('pet-images.destroy');
});

// -------------------------------------------------------
// Public routes — after admin routes
// -------------------------------------------------------
Route::get('/', function () {
    // Single query — fetch the most-recently-added available pet for each featured species.
    $featuredPets = \App\Models\Pet::with('images')
        ->where('status', 'available')
        ->whereIn('species', ['dog', 'cat', 'rabbit', 'bird'])
        ->orderByDesc('id')
        ->get()
        ->unique('species')  // keep only the latest pet per species
        ->values();          // reset to a clean 0-indexed collection for the view

    return view('welcome', compact('featuredPets'));
})->name('home');

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->middleware('auth')->name('volunteer.pending');

// Google OAuth
Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);

Route::resource('pets', PetController::class)->only(['index', 'show']);

// -------------------------------------------------------
// Auth required — all roles
// -------------------------------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::delete('/applications/{application}/cancel', [ApplicationController::class, 'cancel'])->name('applications.cancel');
    Route::get('/applications/{application}/contract', [ContractController::class, 'download'])
        ->name('applications.contract');

    Route::get('/match', [MatchController::class, 'index'])->name('match.index');
    Route::post('/match', [MatchController::class, 'run'])->name('match.run');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    // Favourites (adopters)
    Route::get('/favourites', [FavouriteController::class, 'index'])->name('favourites.index');
    Route::post('/pets/{pet}/favourite', [FavouriteController::class, 'store'])->name('favourites.store');
    Route::delete('/pets/{pet}/favourite', [FavouriteController::class, 'destroy'])->name('favourites.destroy');

    // Success Stories
    Route::get('/stories', [SuccessStoryController::class, 'index'])->name('stories.index');
    Route::get('/stories/create', [SuccessStoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [SuccessStoryController::class, 'store'])->name('stories.store');
});

// Volunteer management + admin-only new features
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
    Route::patch('/volunteers/{user}/approve', [VolunteerController::class, 'approve'])->name('volunteers.approve');
    Route::patch('/volunteers/{user}/revoke', [VolunteerController::class, 'revoke'])->name('volunteers.revoke');

    // Stories moderation
    Route::patch('/stories/{story}/approve', [SuccessStoryController::class, 'approve'])->name('stories.approve');
    Route::delete('/stories/{story}', [SuccessStoryController::class, 'destroy'])->name('stories.destroy');

    // Surrender management
    Route::get('/surrenders', [SurrenderController::class, 'index'])->name('surrenders.index');
    Route::patch('/surrenders/{surrender}/status', [SurrenderController::class, 'updateStatus'])->name('surrenders.updateStatus');
});

// -------------------------------------------------------
// Admin + Volunteer
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin,volunteer'])->group(function () {

    Route::get('/medical/{pet}', [MedicalRecordController::class, 'index'])->name('medical.index');

    Route::get('/home-visits', [MeetGreetController::class, 'index'])->name('home-visits.index');
    Route::post('/home-visits', [MeetGreetController::class, 'store'])->name('home-visits.store');
    Route::patch('/home-visits/{meetGreet}/status', [MeetGreetController::class, 'updateStatus'])->name('home-visits.updateStatus');

    // Post-adoption check-ins
    Route::get('/checkins', [AdoptionCheckinController::class, 'index'])->name('checkins.index');
    Route::patch('/checkins/{checkin}/complete', [AdoptionCheckinController::class, 'complete'])->name('checkins.complete');

    // Pet diary entries
    Route::post('/pets/{pet}/diary', [DiaryEntryController::class, 'store'])->name('diary.store');
    Route::delete('/diary/{entry}', [DiaryEntryController::class, 'destroy'])->name('diary.destroy');
});

// Public surrender form (no auth required)
Route::get('/surrender', [SurrenderController::class, 'create'])->name('surrenders.create');
Route::post('/surrender', [SurrenderController::class, 'store'])->name('surrenders.store');

require __DIR__ . '/auth.php';