<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColumbaryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/columbary', [ColumbaryController::class, 'index'])->name('columbary.index');
    Route::post('/columbary/reserve/{id}', [ColumbaryController::class, 'reserveSlot'])->name('columbary.reserve');
    Route::post('/columbary/paid/{id}', [ColumbaryController::class, 'markAsPaid'])->name('columbary.paid');
    Route::get('/columbary/slot-info/{slotId}', [ColumbaryController::class, 'getSlotInfo'])->name('columbary.slot-info');

    Route::get('/columbary/list', [ColumbaryController::class, 'listSlots'])->name('columbary.list');
    Route::get('/columbary/edit/{id}', [ColumbaryController::class, 'edit'])->name('columbary.edit');
    Route::get('/columbary/floor/{floor}', [ColumbaryController::class, 'getVaults'])->name('columbary.getVaults');
    Route::put('/columbary/update/{id}', [ColumbaryController::class, 'update'])->name('columbary.update');

    Route::get('/slot-details/{slotId}', [HomeController::class, 'getSlotDetails'])->name('slot.details');

    Route::patch('/columbary/{id}/mark-not-available', [ColumbaryController::class, 'markNotAvailable'])
    ->name('columbary.markNotAvailable');

    Route::post('/columbary/create-slots', [ColumbaryController::class, 'createSlots'])->name('columbary.create-slots');

    Route::get('/home', [HomeController::class, 'showSlots'])->name('home');
    Route::post('/reserve-slot', [HomeController::class, 'reserveSlot'])->name('reserve.slot');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
