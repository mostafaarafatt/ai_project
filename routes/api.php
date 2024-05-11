<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DiseasePredictionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('register', [AccountController::class, 'register']);
Route::post('login', [AccountController::class, 'login']);
Route::post('forgot-password', [AccountController::class, 'forgotPassword']);
Route::post('reset-password', [AccountController::class, 'resetPassword']);
Route::post('verify-email', [AccountController::class, 'verifyEmail']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AccountController::class, 'logout']);
    Route::get('user', [AccountController::class, 'user']);

    Route::get('/predict-disease', [DiseasePredictionController::class, 'predictDisease']);
});


Route::get('/symptoms', [DiseasePredictionController::class, 'getSymptoms']);

