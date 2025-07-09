<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\TeamController; 
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ActivitySubscriptionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ContactUsController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\DatingSubscriptionController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ActivityApprovedController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\adminlogincontroller;
use App\Http\Controllers\Admin\InterestController;
use App\Http\Controllers\Admin\VibeController;
use App\Http\Controllers\Admin\ExpenseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/clear-cache', function () {
//     $exitCode = Artisan::call('cache:clear');
//     // $exitCode = Artisan::call('route:clear');
//     // $exitCode = Artisan::call('config:clear');
//     // $exitCode = Artisan::call('view:clear');
//     // return what you want
// });
//=========================================== FRONTEND =====================================================

Route::group(['prefix' => '/'], function () {
    Route::get('/', [HomeController::class, 'index'])->name('/');
});
Route::group(['prefix' => 'contact'], function () {
    Route::get('contact', [HomeController::class, 'contact'])->name('contact');
});
Route::group(['prefix' => 'about'], function () {
    Route::get('about', [HomeController::class, 'about'])->name('about');
});
Route::group(['prefix' => 'privacy_policy'], function () {
    Route::get('privacy_policy', [HomeController::class, 'privacy_policy'])->name('privacy_policy');
});
Route::group(['prefix' => 'terms_and_conditions'], function () {
    Route::get('terms_and_conditions', [HomeController::class, 'terms_and_conditions'])->name('terms_and_conditions');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('products', [HomeController::class, 'products'])->name('products');
});
        
//======================================= ADMIN ===================================================
Route::group(['prifix' => 'admin'], function () {
    Route::group(['middleware'=>'admin.guest'],function(){
 
        Route::get('/admin_index', [adminlogincontroller::class, 'admin_login'])->name('admin_login');
        Route::post('/login_process', [adminlogincontroller::class, 'admin_login_process'])
        ->name('admin_login_process');
    });

Route::group(['middleware'=>'admin.auth'],function(){

 Route::get('/index', [TeamController::class, 'admin_index'])->name('admin_index');
 Route::get('/logout', [adminlogincontroller::class, 'admin_logout'])->name('admin_logout');
 Route::get('/profile', [adminlogincontroller::class, 'admin_profile'])->name('admin_profile');
 Route::get('/view_change_password', [adminlogincontroller::class, 'admin_change_pass_view'])->name('view_change_password');
 Route::post('/admin_change_password', [adminlogincontroller::class, 'admin_change_password'])->name('admin_change_password');

        // Admin Team ------------------------

Route::get('/view_team', [TeamController::class, 'view_team'])->name('view_team');
Route::get('/add_team_view', [TeamController::class, 'add_team_view'])->name('add_team_view');
Route::post('/add_team_process', [TeamController::class, 'add_team_process'])->name('add_team_process');
Route::get('/UpdateTeamStatus/{status}/{id}', [TeamController::class, 'UpdateTeamStatus'])->name('UpdateTeamStatus');
Route::get('/deleteTeam/{id}', [TeamController::class, 'deleteTeam'])->name('deleteTeam');

// Add Notification
Route::match(['get','post'],'/add_notification', [NotificationController::class, 'add_notification'])->name('add_notification');
Route::get('/notifications', [NotificationController::class, 'notifications'])->name('Notification.index');
Route::get('/deletenotification/{id}', [NotificationController::class, 'deletenotification'])->name('deletenotification');

// Admin CRM settings ------------------------
Route::get('/add_settings', [CrmController::class, 'add_settings'])->name('add_settings');
Route::get('/view_settings', [CrmController::class, 'view_settings'])->name('view_settings');
Route::get('/update_settings/{id}', [CrmController::class, 'update_settings'])->name('update_settings');
Route::post('/add_settings_process', [CrmController::class, 'add_settings_process'])->name('add_settings_process');
Route::post('/update_settings_process/{id}', [CrmController::class, 'update_settings_process'])->name('update_settings_process');
Route::get('/deletesetting/{id}', [CrmController::class, 'deletesetting'])->name('deletesetting');

Route::resource('interests', InterestController::class);
Route::get('/intereststatus/{status}/{id}', [InterestController::class, 'updateStatus'])->name('intereststatus');

Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('users/create-or-edit/{id?}', [UserController::class, 'createOrEdit'])->name('users.createOrEdit');
Route::post('users/store-or-update/{id?}', [UserController::class, 'storeOrUpdate'])->name('users.storeOrUpdate');
Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
Route::get('/cities/{stateId}', [UserController::class, 'getCitiesByState']);
Route::get('/user/status/{action}/{id}', [UserController::class, 'updateStatus'])->name('userststatus');


Route::get('activity-coin', [UserController::class, 'activityCoinView'])->name('activityCoinView');
Route::get('/user/add-activity-coin/{id}', [UserController::class, 'addcoin'])->name('activityCoin');
Route::post('/user/{id}/create-coin', [UserController::class, 'createCoin'])->name('createCoin');


// Route::get('/vibes', [VibeController::class, 'index'])->name('vibe.index');

Route::get('/vibe', [VibeController::class, 'index'])->name('vibe.index');
Route::get('/vibe/create', [VibeController::class, 'create'])->name('vibe.create');
Route::post('/vibe/store', [VibeController::class, 'store'])->name('vibe.store');
Route::get('/vibe/edit/{id}', [VibeController::class, 'edit'])->name('vibe.edit');
Route::put('/vibe/update/{id}', [VibeController::class, 'update'])->name('vibe.update');
Route::delete('/vibe/{id}', [VibeController::class, 'destroy'])->name('vibe.destroy');
Route::patch('/vibe/update-status/{id}', [VibeController::class, 'updateStatus'])->name('vibe.updateStatus');


Route::get('/expense', [ExpenseController::class, 'index'])->name('expense.index');
Route::get('/expense/create', [ExpenseController::class, 'create'])->name('expense.create');
Route::post('/expense/store', [ExpenseController::class, 'store'])->name('expense.store');
Route::get('/expense/edit/{id}', [ExpenseController::class, 'edit'])->name('expense.edit');
Route::put('/expense/update/{id}', [ExpenseController::class, 'update'])->name('expense.update');
Route::delete('/expense/{id}', [ExpenseController::class, 'destroy'])->name('expense.destroy');
Route::patch('/expense/update-status/{id}', [ExpenseController::class, 'updateStatus'])->name('expense.updateStatus');

Route::get('/panding-activity', [ActivityApprovedController::class, 'pandingactivity'])->name('pandingactivity');
Route::get('/approved-activity', [ActivityApprovedController::class, 'approvedactivity'])->name('approvedactivity');
Route::get('/activity/status/{action}/{id}', [ActivityApprovedController::class, 'updateStatus'])->name('activitystatus');


Route::get('coin-category', [CategoryController::class, 'index'])->name('coinCategoryindex');
Route::get('coin-category-create', [CategoryController::class, 'create'])->name('coinCategoryCreate');
Route::post('coin-category-store', [CategoryController::class, 'store'])->name('coinCategoryCreate.store');
Route::get('/coin-category/{id}/edit', [CategoryController::class, 'edit'])->name('coin-category.edit');
Route::put('/coin-category/{id}', [CategoryController::class, 'update'])->name('coin-category.update');
Route::delete('/coin-category-delete/{id}', [CategoryController::class, 'destroy'])->name('coin-category.delete');
Route::get('/coin-category/{status}/{id}/update-status', [CategoryController::class, 'updateStatus'])->name('coin-category.update-status');


Route::get('dating-subscription', [DatingSubscriptionController::class, 'index'])->name('datingsubscriptionindex');
Route::get('dating-subscription-create', [DatingSubscriptionController::class, 'create'])->name('datingsubscriptionCreate');
Route::post('dating-subscription-store', [DatingSubscriptionController::class, 'store'])->name('datingSubscription.store');
Route::get('/dating-subscription/{id}/edit', [DatingSubscriptionController::class, 'edit'])->name('datingsubscription.edit');
Route::put('/dating-subscription/{id}', [DatingSubscriptionController::class, 'update'])->name('datingSubscription.update');
Route::delete('/dating-subscription-delete/{id}', [DatingSubscriptionController::class, 'destroy'])->name('datingsubscription.delete');

Route::get('/dating-subscription/{status}/{id}/update-status', [DatingSubscriptionController::class, 'updateStatus'])->name('dating-subscription.update-status');

Route::get('activity-subscription', [ActivitySubscriptionController::class, 'index'])->name('activitysubscriptionindex');
Route::get('activity-subscription-create', [ActivitySubscriptionController::class, 'create'])->name('activitysubscriptionCreate');
Route::post('activity-subscription-store', [ActivitySubscriptionController::class, 'store'])->name('activitySubscription.store');
Route::get('/activity-subscription/{id}/edit', [ActivitySubscriptionController::class, 'edit'])->name('activitysubscription.edit');
Route::put('/Activity-subscription/{id}', [ActivitySubscriptionController::class, 'update'])->name('activitySubscription.update');
Route::delete('/activity-subscription-delete/{id}', [ActivitySubscriptionController::class, 'destroy'])->name('activitysubscription.delete');

Route::get('/activity-subscription/{status}/{id}/update-status', [ActivitySubscriptionController::class, 'updateStatus'])->name('activity-subscription.update-status');

    });

});



