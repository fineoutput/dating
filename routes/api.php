<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InterestController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\DatingController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\CashfreeController;
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

Route::get('phone-numbers', [AuthController::class, 'getPhoneNumbers']);
Route::post('/check_numbers', [AuthController::class, 'checkNumbers']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/contact-store', [AuthController::class, 'contact_store']);
    Route::post('/contact-update', [AuthController::class, 'contact_update']);
    Route::get('/contact-get', [AuthController::class, 'contact_get']);

    Route::post('/user/device', [AuthController::class, 'fcmUpdate']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('activity-store', [ActivityController::class, 'activitystore']);
    Route::post('activity-edit', [ActivityController::class, 'activityedit']);
    Route::get('activitys', [ActivityController::class, 'activitys']);
    Route::post('get-activity-detaile', [ActivityController::class, 'getActivitydetailes']);
    Route::post('for-you-activity-detaile', [ActivityController::class, 'foryouActivitydetailes']);
    Route::get('user-activitys', [ActivityController::class, 'useractivitys']);
    Route::get('user-old-activitys', [ActivityController::class, 'useroldactivitys']);
    Route::get('user-interest-activitys', [ActivityController::class, 'userinterestactivitys']);
    Route::get('user-interest-number', [ActivityController::class, 'userinterestnumber']);
    Route::get('user-confirm-activitys', [ActivityController::class, 'userconfirmactivitys']);
    Route::get('user-liked-activitys', [ActivityController::class, 'likedactivitys']);
    Route::get('for-you-activitys', [ActivityController::class, 'foryouactivitys']);
  
    Route::get('find-matching-users-activity', [ActivityController::class, 'findMatchingactivity']);
    Route::get('find-matching-activity', [ActivityController::class, 'findactivity']);
    Route::get('interest-activity', [ActivityController::class, 'interestactivity']);
    Route::get('vibe-activity-count', [ActivityController::class, 'vibeactivitycount']);
    Route::post('vibe-activity-details', [ActivityController::class, 'vibeactivitydetails']);
    Route::post('filter-activity', [ActivityController::class, 'filteractivity']);

    Route::get('friend-count', [ActivityController::class, 'friendcount']);
    Route::get('contact-users', [ActivityController::class, 'contact_users']);
    Route::get('friend-count-one', [ActivityController::class, 'friendcount_one']);

    Route::post('add-interest', [InterestController::class, 'addinterest']);
    Route::post('add-like', [InterestController::class, 'like_activity']);
    Route::post('remove-like-activity', [InterestController::class, 'remove_like_activity']);
    Route::post('add-confirm', [InterestController::class, 'addconfirms']);
    Route::post('get-confirmed-users', [InterestController::class, 'getConfirmedUsers']);
    Route::post('remove-interest', [InterestController::class, 'removeinterest']);
    Route::post('invite-interest', [InterestController::class, 'inviteinterest']);
    Route::post('get-user-interest', [InterestController::class, 'getuserinterest']);
    Route::post('confirm-user-interest', [InterestController::class, 'confirmuserinterest']);
    Route::post('add-confirm-user-interest', [InterestController::class, 'confirm_user_interest']);
    

    Route::post('chat-send', [ChatController::class, 'sendMessage']);
    Route::post('chat-messages', [ChatController::class, 'getMessages']);
    Route::post('chat-update-status', [ChatController::class, 'updateMessageStatus']);

    Route::get('user-profile', [AuthController::class, 'userprofile']);
    
    Route::post('update-profile', [AuthController::class, 'updateProfile']);
    Route::post('update-city', [AuthController::class, 'updateCity']);
    Route::post('update-latlong', [AuthController::class, 'updatelatlong']);



    Route::post('find-matching-users-interest', [DatingController::class, 'findMatchingUsers']);
    Route::post('matching-users-detailes', [DatingController::class, 'MatchingUsersdetailes']);
    Route::post('dating-preference', [DatingController::class, 'datingpreference']);
    Route::post('find-swipe', [DatingController::class, 'findswipe']);

    Route::post('/cupidmatch', [DatingController::class, 'cupidmatch']);

    Route::get('/cupidmatch-friend', [DatingController::class, 'cupidMatchFriend']);
    Route::get('pre-dating', [DatingController::class, 'pre_dating']);

    
    Route::post('/accept-cupid', [DatingController::class, 'acceptCupid']);
    Route::post('/accept-slide', [DatingController::class, 'acceptslide']);

    Route::post('/update-cupid-match', [DatingController::class, 'updateCupidMatch']);

    Route::post('/subscription-list', [SubscriptionController::class, 'subscriptionlists']);

    Route::post('user-interactions', [DatingController::class, 'handleUserInteractions']);
    Route::get('matched-user', [DatingController::class, 'matched_user']);

    Route::get('user-interactions-count', [DatingController::class, 'getUserInteractionsCount']);


    Route::post('/get-user-data', [DatingController::class, 'getUserData']);
    Route::post('/report-user', [DatingController::class, 'reportUser']);

    Route::get('/coin-categories', [SubscriptionController::class, 'subscriptionlist']);
    Route::get('/dating-subscription-list', [SubscriptionController::class, 'datingsubscriptionlists']);

    // Route::post('/subscribe', [CashfreeController::class, 'createSubscriptionOrder']);

    Route::post('/create-subscription-order', [CashfreeController::class, 'createSubscriptionOrder']);
    Route::post('/create-subscription-order-activitys', [CashfreeController::class, 'createSubscriptionOrderActivitys']);

    Route::post('/verify-payment', [CashfreeController::class, 'verifyPayment']);
    Route::post('/verify-payment-activitys', [CashfreeController::class, 'verifyPaymentActivitys']);

    Route::post('/update-confirm', [ActivityController::class, 'updateConfirm']);
    Route::post('/update-pactup', [ActivityController::class, 'acceptpactup']);
    Route::post('update-number', [ActivityController::class, 'acceptnumber']);

    Route::get('user-interest', [InterestController::class, 'userinterest']);
    Route::get('pactup-request', [ActivityController::class, 'pactup_request']);
    Route::get('pactup', [ActivityController::class, 'pactup']);
    Route::get('verify-City', [ActivityController::class, 'verifyCity']);
    Route::get('admin-city', [ActivityController::class, 'admincity']);


});   

Route::get('interest', [InterestController::class, 'interest']);
Route::post('cashfree/webhook', [CashfreeController::class, 'handleWebhook']);


Route::get('vibes', [InterestController::class, 'vibes']);
Route::get('expense', [InterestController::class, 'expense']);



