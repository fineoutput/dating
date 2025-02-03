<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DatingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SubscriptionController;
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

// Route::post('signup', [AuthController::class, 'signup']);
Route::post('verify_auth_otp', [AuthController::class, 'verify_auth_otp']);
Route::post('login', [AuthController::class, 'signup']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('activity-store', [ActivityController::class, 'activitystore']);
    Route::get('activitys', [ActivityController::class, 'activitys']);
    Route::post('get-activity-detaile', [ActivityController::class, 'getActivitydetailes']);
    Route::get('user-activitys', [ActivityController::class, 'useractivitys']);
  
    Route::get('find-matching-users-activity', [ActivityController::class, 'findMatchingactivity']);
    Route::get('vibe-activity-count', [ActivityController::class, 'vibeactivitycount']);
    Route::post('vibe-activity-details', [ActivityController::class, 'vibeactivitydetails']);
    Route::post('filter-activity', [ActivityController::class, 'filteractivity']);


    Route::post('add-interest', [InterestController::class, 'addinterest']);
    Route::post('get-user-interest', [InterestController::class, 'getuserinterest']);
    Route::post('confirm-user-interest', [InterestController::class, 'confirmuserinterest']);
    Route::post('add-confirm-user-interest', [InterestController::class, 'confirm_user_interest']);

    Route::post('chat-send', [ChatController::class, 'sendMessage']);
    Route::get('chat-messages', [ChatController::class, 'getMessages']);
    Route::post('chat-update-status', [ChatController::class, 'updateMessageStatus']);

    Route::get('user-profile', [AuthController::class, 'userprofile']);


    Route::get('find-matching-users-interest', [DatingController::class, 'findMatchingUsers']);

    Route::post('/cupidmatch', [DatingController::class, 'cupidmatch']);
    Route::post('/update-cupid-match', [DatingController::class, 'updateCupidMatch']);

    Route::post('/subscription-list', [SubscriptionController::class, 'subscriptionlist']);

});   



Route::get('interest', [InterestController::class, 'interest']);
Route::get('vibes', [InterestController::class, 'vibes']);
Route::get('expense', [InterestController::class, 'expense']);



