<?php

use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\VotingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Secure Online Voting System
|--------------------------------------------------------------------------
|
| Routes are protected using:
| - CSRF Protection (automatic via Laravel)
| - Admin Middleware for admin-only pages
| - Session-based authentication for voters
|
*/

// Home / Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth middleware redirect target (routes to admin login)
Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');

// =============================================
// Admin Authentication Routes
// =============================================
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'adminLogin'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'adminLogout'])->name('logout');
});

// =============================================
// Admin Protected Routes
// =============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Candidate Management
    Route::prefix('candidates')->name('candidates.')->group(function () {
        Route::get('/', [CandidateController::class, 'index'])->name('index');
        Route::get('/create', [CandidateController::class, 'create'])->name('create');
        Route::post('/', [CandidateController::class, 'store'])->name('store');
        Route::get('/{candidate}/edit', [CandidateController::class, 'edit'])->name('edit');
        Route::put('/{candidate}', [CandidateController::class, 'update'])->name('update');
        Route::delete('/{candidate}', [CandidateController::class, 'destroy'])->name('destroy');
    });
});

// =============================================
// Voter Authentication Routes
// =============================================
Route::prefix('voter')->name('voter.')->group(function () {
    Route::get('/login', [AuthController::class, 'showVoterLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'voterLogin'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'voterLogout'])->name('logout');
});

// =============================================
// Voting Routes (Session-based voter auth)
// =============================================
Route::prefix('vote')->name('voting.')->group(function () {
    Route::get('/', [VotingController::class, 'index'])->name('index');
    Route::post('/cast', [VotingController::class, 'castVote'])->name('submit');
    Route::get('/success', [VotingController::class, 'success'])->name('success');
    Route::get('/already-voted', [VotingController::class, 'alreadyVoted'])->name('already-voted');
});

// =============================================
// Public Results Route
// =============================================
Route::get('/results', [ResultController::class, 'index'])->name('results.index');
