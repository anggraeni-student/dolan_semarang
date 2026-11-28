<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/destinasi/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/destinasi/{slug}/review', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/panduan', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/panduan/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/komunitas', [CommunityController::class, 'index'])->name('community.index');
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/u/{username}', [ProfileController::class, 'show'])->name('profiles.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');
});
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

// Butuh login
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
    Route::get('/destinasi/{slug}/tulis-review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/destinasi/{slug}/tulis-review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/komunitas', [CommunityController::class, 'store'])->name('community.store');

    // Fitur Wishlist (Khusus Login)
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{destination}/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Fitur Trip Planner / Rencana Perjalanan (Khusus Login)
    Route::get('/rencana-trip', [ItineraryController::class, 'index'])->name('itineraries.index');
    Route::post('/rencana-trip', [ItineraryController::class, 'store'])->name('itineraries.store');
    Route::get('/rencana-trip/{itinerary}', [ItineraryController::class, 'show'])->name('itineraries.show');
    Route::delete('/rencana-trip/{itinerary}', [ItineraryController::class, 'destroy'])->name('itineraries.destroy');
    Route::post('/rencana-trip/{itinerary}/items', [ItineraryController::class, 'storeItem'])->name('itineraries.items.store');
    Route::delete('/rencana-trip/{itinerary}/items/{item}', [ItineraryController::class, 'destroyItem'])->name('itineraries.items.destroy');
});