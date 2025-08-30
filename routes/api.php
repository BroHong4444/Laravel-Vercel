<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserReportController;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/get-users', [UserController::class, 'getUser']);
    Route::get('/auth-id', function (Request $request) {
        return response()->json(auth()->id());
    });
    Route::get('/search/{query}',  [UserController::class, 'search']);
    Route::post('/create-user', [UserController::class, 'createUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    Route::put('/update-user/{id}', [UserController::class, 'updateUser']);
    Route::get('/get-profile', [UserController::class, 'getUserProfile']);

    // Route for creating report
    Route::post('/send-report', [ReportController::class, 'createReport']);
    Route::get('/get-report', [ReportController::class, 'getReport']);

    // Route for get userReport
    Route::get('/user-report', [UserReportController::class, 'getUserReport']);
    Route::get('/user-report/{id}', [UserReportController::class, 'getUserReportById']);
});


// Test route
Route::post('/post-method', function () {
    return ['message' => 'This is post method'];
});
