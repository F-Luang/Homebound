<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('pets', PetController::class)->except(['index', 'show']);
    Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus']);
    Route::resource('medical-records', MedicalRecordController::class)->except(['index', 'show']);
});

Route::middleware(['auth', 'role:admin,volunteer'])->group(function () {
    Route::get('applications', [ApplicationController::class, 'index']);
    Route::get('meet-greets', [MeetGreetController::class, 'index']);
});

