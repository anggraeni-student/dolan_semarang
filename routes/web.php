<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DestinationController::class, 'index'])->name('home');

Route::prefix('wisata')->name('wisata.')->group(function () {
    Route::get('/', [DestinationController::class, 'index'])->name('index');
    Route::get('/tambah', [DestinationController::class, 'create'])->name('create');
    Route::post('/', [DestinationController::class, 'store'])->name('store');
    Route::get('/{destination}', [DestinationController::class, 'show'])->name('show');
    Route::get('/{destination}/edit', [DestinationController::class, 'edit'])->name('edit');
    Route::put('/{destination}', [DestinationController::class, 'update'])->name('update');
    Route::delete('/{destination}', [DestinationController::class, 'destroy'])->name('destroy');
    Route::post('/{destination}/review', [DestinationController::class, 'storeReview'])->name('review.store');
});