<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\MeetGreetController;
use App\Http\Controllers\MatchController;

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
});

// -------------------------------------------------------
// Public routes — after admin routes
// -------------------------------------------------------
Route::get('/', fn() => redirect()->route('pets.index'));

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

    Route::get('/match', [MatchController::class, 'index'])->name('match.index');
    Route::post('/match', [MatchController::class, 'run'])->name('match.run');
});

// -------------------------------------------------------
// Admin + Volunteer
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin,volunteer'])->group(function () {

    Route::get('/medical/{pet}', [MedicalRecordController::class, 'index'])->name('medical.index');

    Route::get('/meet-greets', [MeetGreetController::class, 'index'])->name('meet-greets.index');
    Route::post('/meet-greets', [MeetGreetController::class, 'store'])->name('meet-greets.store');
    Route::patch('/meet-greets/{meetGreet}/status', [MeetGreetController::class, 'updateStatus'])->name('meet-greets.updateStatus');
});

require __DIR__ . '/auth.php';