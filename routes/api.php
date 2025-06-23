<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Api\Employee\AuthController;
use App\Http\Controllers\CelebrantsController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServerTimeController;
use App\Http\Controllers\AttendanceController;
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

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
    Route::get('check', [AuthController::class, 'checkAuth']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
    });
});

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

// Employee Routes
Route::get('/employees/{employee}/signature', [EmployeeController::class, 'getSignature']);

// Celebrants Routes
Route::get('/today-celebrants', [CelebrantsController::class, 'getTodayCelebrants']);
Route::post('/dismiss-celebrants', [CelebrantsController::class, 'dismissCelebrants']);

// Notification Routes
Route::get('notifications/health', [NotificationsController::class, 'healthCheck']);

// Server Time Routes
Route::get('/server-time', [ServerTimeController::class, 'getTime']);
Route::get('/verify-timestamp/{timestamp}/{hash}', [ServerTimeController::class, 'verifyTimestamp']);

// Employee information API endpoint
Route::get('/employee-info', [AttendanceController::class, 'getEmployeeInfo'])->middleware('auth');

// Store attendance capture
Route::post('/attendance/store', [AttendanceController::class, 'storeAttendanceCapture'])->middleware('auth');

// Profile Routes
Route::middleware('auth:sanctum')->prefix('profile')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::get('/details', [App\Http\Controllers\Api\ProfileController::class, 'details']);
    Route::post('/update', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    Route::post('/update-signature', [App\Http\Controllers\Api\ProfileController::class, 'updateSignature']);
});

// User Details Route
Route::middleware('auth:sanctum')->get('/user-details', [App\Http\Controllers\Api\ProfileController::class, 'getUserDetails']);

// Messaging API Routes
Route::middleware(['auth:sanctum'])->prefix('messages')->group(function () {
    Route::get('/{conversationId}', 'App\Http\Controllers\Api\MessageController@getMessages');
    Route::post('/send', 'App\Http\Controllers\Api\MessageController@sendMessage');
    Route::post('/read', 'App\Http\Controllers\Api\MessageController@markAsRead');
    Route::delete('/{id}', 'App\Http\Controllers\Api\MessageController@delete');
    Route::post('/typing', 'App\Http\Controllers\Api\MessageController@typing');
});

// Add conversations API route for messaging sync
Route::middleware(['auth:sanctum'])->get('/conversations', 'App\Http\Controllers\Api\MessageController@getConversations');

// API User routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/{id}/profile', 'App\\Http\\Controllers\\Api\\UserController@getProfile');
    Route::post('/users/block', 'App\\Http\\Controllers\\Api\\UserController@blockUser');
    Route::post('/users/unblock', 'App\\Http\\Controllers\\Api\\UserController@unblockUser');
});

// WebRTC routes
Route::get('/webrtc/ice-servers', 'App\\Http\\Controllers\\Api\\WebRTCController@getIceServers');
