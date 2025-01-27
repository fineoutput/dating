<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\ActivityController;
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

Route::post('signup', [AuthController::class, 'signup']);
Route::post('verify_auth_otp', [AuthController::class, 'verify_auth_otp']);
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('activity-store', [ActivityController::class, 'activitystore']);
    Route::get('activitys', [ActivityController::class, 'activitys']);
    Route::get('user-activitys', [ActivityController::class, 'useractivitys']);
    Route::get('find-matching-users-interest', [ActivityController::class, 'findMatchingUsers']);
    Route::get('find-matching-users-activity', [ActivityController::class, 'findMatchingactivity']);

    Route::post('add-interest', [InterestController::class, 'addinterest']);
    Route::post('get-user-interest', [InterestController::class, 'getuserinterest']);
    Route::post('confirm-user-interest', [InterestController::class, 'confirmuserinterest']);


    Route::get('user-profile', [AuthController::class, 'userprofile']);
});


Route::get('interest', [InterestController::class, 'interest']);
Route::get('vibes', [InterestController::class, 'vibes']);
Route::get('expense', [InterestController::class, 'expense']);



