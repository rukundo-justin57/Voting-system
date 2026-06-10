<?php

use App\Http\Controllers\ResultController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Secure Online Voting System
|--------------------------------------------------------------------------
|
| These routes are protected by Laravel Sanctum for API token authentication.
| The election results endpoint returns structured JSON data consumable
| by any JavaScript/Node.js client.
|
*/

// =============================================
// Public API Route (no auth required for results)
// =============================================
Route::get('/election/results', [ResultController::class, 'apiResults'])
    ->name('api.results');

// =============================================
// Protected API Route (Sanctum token required)
// =============================================
Route::middleware('auth:sanctum')->group(function () {
    // Add more protected API endpoints here as needed
    // e.g., candidate management, vote verification, etc.

    /**
     * Get election results - protected endpoint.
     * Requires a valid Sanctum API token in the Authorization header.
     *
     * Example: Authorization: Bearer <sanctum-token>
     */
    Route::get('/election/results/secure', [ResultController::class, 'apiResults'])
        ->name('api.results.secure');
});
