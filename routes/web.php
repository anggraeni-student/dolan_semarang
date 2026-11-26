<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DestinationController::class, 'index'])->name('home');
Route::get('/review', [DestinationController::class, 'reviews'])->name('reviews.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('wisata')->name('wisata.')->group(function () {

    Route::get('/', [DestinationController::class, 'index'])->name('index');
    Route::get('/{destination}/reviews-json', [DestinationController::class, 'reviewsJson'])->name('reviews.json');

    Route::middleware('auth')->group(function () {
        Route::get('/tambah', [DestinationController::class, 'create'])->name('create');
        Route::post('/', [DestinationController::class, 'store'])->name('store');
        Route::get('/{destination}/edit', [DestinationController::class, 'edit'])->name('edit');
        Route::put('/{destination}', [DestinationController::class, 'update'])->name('update');
        Route::delete('/{destination}', [DestinationController::class, 'destroy'])->name('destroy');
        Route::post('/{destination}/review', [DestinationController::class, 'storeReview'])->name('review.store');
    });

    Route::get('/{destination}', [DestinationController::class, 'show'])->name('show');
});