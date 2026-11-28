<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\ModerationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Semua destinasi (bukan lagi default ke kategori kuliner)
Route::get('/destinasi', [DestinationController::class, 'index'])->name('destinations.index');
// Form tambah destinasi — HARUS di atas /destinasi/{slug} agar tidak tertangkap
Route::get('/destinasi/baru', [DestinationController::class, 'create'])->middleware('auth')->name('destinations.create');
Route::get('/destinasi/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Semua review komunitas (bukan lagi default ke Kota Lama)
Route::get('/review', [ReviewController::class, 'all'])->name('reviews.all');

// Pencarian global
Route::get('/search', SearchController::class)->name('search');

Route::get('/destinasi/{slug}', [DestinationController::class, 'show'])->name('destinations.show');
Route::get('/destinasi/{slug}/review', [ReviewController::class, 'index'])->name('reviews.index');
Route::get('/panduan', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/panduan/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/komunitas', [CommunityController::class, 'index'])->name('community.index');
Route::get('/komunitas/{thread}', [CommunityController::class, 'show'])->whereNumber('thread')->name('community.show');
Route::get('/peta', [MapController::class, 'index'])->name('map.index');
Route::get('/u/{username}', [ProfileController::class, 'show'])->name('profiles.show');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/masuk', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->name('register.store');

    // Lupa password (nama route password.* dipakai notifikasi bawaan Laravel)
    Route::get('/lupa-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/lupa-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});
Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

// Butuh login
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'show'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // Review untuk destinasi tertentu
    Route::get('/destinasi/{slug}/tulis-review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/destinasi/{slug}/tulis-review', [ReviewController::class, 'store'])->name('reviews.store');

    // Thread komunitas + balasan
    Route::post('/komunitas', [CommunityController::class, 'store'])->name('community.store');
    Route::post('/komunitas/{thread}/balas', [CommunityController::class, 'reply'])->whereNumber('thread')->name('community.reply');

    // Simpan destinasi baru dari user
    Route::post('/destinasi/baru', [DestinationController::class, 'store'])->name('destinations.store');

    // Edit profil & preferensi sendiri
    Route::post('/u/{username}/update', [ProfileController::class, 'update'])->name('profiles.update');

    // Lapor konten (review / thread / balasan / destinasi)
    Route::post('/lapor', [ReportController::class, 'store'])->name('reports.store');

    // Kotak chat dengan tim admin
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/{conversation}/kirim', [ChatController::class, 'send'])->name('chat.send');

    // Moderasi admin (bisa diakses dari dashboard biasa, tanpa buka /admin)
    Route::get('/moderasi', [ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderasi/laporan/{report}/tindak', [ModerationController::class, 'action'])->name('moderation.action');
    Route::post('/moderasi/hapus', [ModerationController::class, 'destroy'])->name('moderation.destroy');
});
