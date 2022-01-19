<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/// auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/// rotas protegidas por token de usuario
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    /// resourses
    /// favorites
    Route::get('/favorites/show', [FavoritesController::class, 'show']);
    Route::get('/favorites/delete/{id}', [FavoritesController::class, 'delete']);
    Route::post('/favorites/store', [FavoritesController::class, 'store']);
    Route::post('/favorites/check', [FavoritesController::class, 'check']);

    /// watchlist
    Route::get('/watchlist/show', [WatchlistController::class, 'show']);
    Route::get('/watchlist/delete/{id}', [WatchlistController::class, 'delete']);
    Route::post('/watchlist/store', [WatchlistController::class, 'store']);
    Route::post('/watchlist/check', [WatchlistController::class, 'check']);

    /// auth
    Route::post('/logout', [AuthController::class, 'logout']);
});