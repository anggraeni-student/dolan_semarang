<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DestinationController::class, 'index'])->name('home');

Route::prefix('wisata')->name('wisata.')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('index');

    Route::get('/tambah', [DestinationController::class, 'create'])->name('create');

    Route::post('/', [DestinationController::class, 'store'])->name('store');
    
    Route::get('/{destination}', [DestinationController::class, 'show'])->name('show');

    Route::post('/{destination}/review', [DestinationController::class, 'storeReview'])->name('review.store');
});