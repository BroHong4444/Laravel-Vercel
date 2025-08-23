<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/hello', function () {
//     return response()->json(['message' => 'Hello World!']);
// });

// Route::get('/hello', function () {
//     return response()->json(['message' => 'Hello World!']);
// });

// Route::get('/users', function () {
//     return response()->json(['users' => User::all()]);
// });

// Route::prefix('benchmark')->group(function () {
//     Route::get('/users/{iterations?}', [BenchmarkController::class, 'users']);
// });

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
});

// Route::controller(UserController::class)
//     ->middleware('auth:api') // Protecting the get-all-user route
//     ->group(function () {
//         // Route::get('/get-users', 'getAllUser'); // Protected
//         Route::get('/get-users', 'getUsers');
//         Route::get('/auth-id', function (Request $request) {
//             return response()->json(auth()->id());
//         });
//         Route::get('/search/{query}',  'search');
//         Route::get('/get-report', 'getUserReport');
//         Route::get('/get-user/{id}', 'getOne');
//         Route::post('/create-user', 'createUser');
//         Route::delete('/delete-user/{id}', 'deleteUser');
//         Route::put('/update-user/{id}', 'updateUser');
//     });

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/get-user', [UserController::class, 'getUser']);

    Route::get('/auth-id', function (Request $request) {
        return response()->json(auth()->id());
    });

    Route::get('/search/{query}',  [UserController::class, 'search']);
    Route::get('/get-report', [UserController::class, 'getUserReport']);
    Route::get('/get-user/{id}', [UserController::class, 'getOne']);
    Route::post('/create-user', [UserController::class, 'createUser']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser']);
    Route::put('/update-user/{id}', [UserController::class, 'updateUser']);

    // Route for creating report
    Route::post('/send-report', [ReportController::class, 'createReport']);
    Route::get('/get-report', [ReportController::class, 'getReport']);
});


// Test route
Route::post('/post-method', function () {
    return ['message' => 'This is post method'];
});
