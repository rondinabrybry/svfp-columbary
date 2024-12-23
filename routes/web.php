<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColumbarySlotController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VaultController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'isAdmin'])
    ->name('dashboard');

Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/columbary/all', [ColumbarySlotController::class, 'Loadindex'])->name('columbary.loadIndex');
    Route::get('/manage/all', [ColumbarySlotController::class, 'loadAllSlots'])->name('columbary.loadAll');
    Route::get('/columbary', [ColumbarySlotController::class, 'index'])->name('columbary.index');
    Route::get('/columbary/list', [ColumbarySlotController::class, 'listSlots'])->name('columbary.list');
    Route::get('/columbary/edit/{id}', [ColumbarySlotController::class, 'edit'])->name('columbary.edit');
    Route::put('/columbary/update/{id}', [ColumbarySlotController::class, 'update'])->name('columbary.update');
    Route::post('/columbary/create-slots', [ColumbarySlotController::class, 'createSlots'])->name('columbary.create-slots');
    Route::put('/columbary/{id}/make-available', [ColumbarySlotController::class, 'makeAvailable'])->name('columbary.makeAvailable');
    Route::patch('/columbary/{id}/mark-not-available', [ColumbarySlotController::class, 'markNotAvailable'])->name('columbary.markNotAvailable');


    Route::post('/columbary/reserve/{id}', [PaymentController::class, 'reserveSlot'])->name('columbary.reserve');
    Route::post('/columbary/paid/{id}', [PaymentController::class, 'markAsPaid'])->name('columbary.paid');


    Route::get('/columbary/floor/{floor}', [VaultController::class, 'getVaults'])->name('columbary.getVaults');


    Route::get('/columbary/slot-info/{slotId}', [ColumbarySlotController::class, 'getSlotInfo'])->name('columbary.slot-info');


    Route::get('/slot-details/{slotId}', [HomeController::class, 'getSlotDetails'])->name('slot.details');


    Route::get('/home', [HomeController::class, 'showSlots'])->name('home');
    Route::post('/reserve-slot', [HomeController::class, 'reserveSlot'])->name('reserve.slot');



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
