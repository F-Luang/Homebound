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
    // Show one featured pet per species (most recently added)
    $featuredPets = collect(['dog', 'cat', 'rabbit', 'bird'])->map(function ($species) {
        return \App\Models\Pet::with('images')
            ->where('status', 'available')
            ->where('species', $species)
            ->latest()
            ->first();
    })->filter(); // removes nulls if no pet of that species exists

    return view('welcome', compact('featuredPets'));
})->name('home');

Route::get('/pending-approval', function () {
    return view('auth.pending');
})->middleware('auth')->name('volunteer.pending');

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
});

// Volunteer management
Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
Route::patch('/volunteers/{user}/approve', [VolunteerController::class, 'approve'])->name('volunteers.approve');
Route::patch('/volunteers/{user}/revoke', [VolunteerController::class, 'revoke'])->name('volunteers.revoke');

// -------------------------------------------------------
// Admin + Volunteer
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin,volunteer'])->group(function () {

    Route::get('/medical/{pet}', [MedicalRecordController::class, 'index'])->name('medical.index');

    Route::get('/home-visits', [MeetGreetController::class, 'index'])->name('home-visits.index');
    Route::post('/home-visits', [MeetGreetController::class, 'store'])->name('home-visits.store');
    Route::patch('/home-visits/{meetGreet}/status', [MeetGreetController::class, 'updateStatus'])->name('home-visits.updateStatus');
});

require __DIR__ . '/auth.php';