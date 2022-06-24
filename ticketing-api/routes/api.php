<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TiketController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:api');

Route::get('/tiket', [TiketController::class, 'index']);
Route::get('/tiket/{id}', [TiketController::class, 'show']);
Route::post('/tiket', [TiketController::class, 'store']);
Route::put('/tiket/{id}', [TiketController::class, 'update'])->middleware('auth:api');
Route::delete('/tiket/{id}', [TiketController::class, 'destroy'])->middleware('auth:api');
Route::get('/tiket/search/{email}', [TiketController::class, 'searchByEmail']);
Route::get('/tiket/cariid/{id_tiket}', [TiketController::class, 'searchByIDTiket']);
Route::put('/tiket/checkin/{id}', [TiketController::class, 'changeStatus']);