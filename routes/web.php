<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BracketController;
use App\Http\Controllers\CasualMatchController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PlayHistoryController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Middleware\AdminMiddleware;

// Public landing page (accessible whether logged in or not)
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// Guest-only routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.post');
});

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/players', [AdminController::class, 'players'])->name('players');
    Route::delete('/players/{user}', [AdminController::class, 'deletePlayer'])->name('players.delete');

    Route::get('/events', [AdminController::class, 'events'])->name('events');
    Route::delete('/events/{event}', [AdminController::class, 'deleteEvent'])->name('events.delete');
    Route::put('/events/{event}/status', [AdminController::class, 'updateEventStatus'])->name('events.status');

    Route::get('/events/featured/create', [AdminController::class, 'createFeatured'])->name('featured.create');
    Route::post('/events/featured', [AdminController::class, 'storeFeatured'])->name('featured.store');

    Route::get('/games', [AdminController::class, 'games'])->name('games');
    Route::put('/games/{game}/result', [AdminController::class, 'overrideResult'])->name('games.result');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard (players redirected to events list)
    Route::get('/dashboard', function () {
        return redirect()->route('events.index');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Events
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/join', [EventController::class, 'join'])->name('events.join');

    // Calendar
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');

    // Games
    Route::get('/events/{event}/games/create', [GameController::class, 'create'])->name('games.create');
    Route::post('/events/{event}/games', [GameController::class, 'store'])->name('games.store');
    Route::get('/games/{game}', [GameController::class, 'show'])->name('games.show');
    Route::put('/games/{game}/result', [GameController::class, 'recordResult'])->name('games.result');

    // Casual matches
    Route::get('/events/{event}/casual/create', [CasualMatchController::class, 'create'])->name('casual.create');
    Route::post('/events/{event}/casual', [CasualMatchController::class, 'store'])->name('casual.store');

    // Brackets
    Route::get('/events/{event}/bracket', [BracketController::class, 'show'])->name('bracket.show');
    Route::post('/events/{event}/bracket/generate', [BracketController::class, 'generate'])->name('bracket.generate');

    // Play history
    Route::get('/history', [PlayHistoryController::class, 'index'])->name('history');
});

