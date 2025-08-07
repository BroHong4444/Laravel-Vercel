<?php

use App\Http\Controllers\BenchmarkController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::post('/post-method', function () {
    return ['message' => 'This is post method'];
});

Route::get('/hello', function () {
    return response()->json(['message' => 'Hello World!']);
});

Route::get('/users', function () {
    return response()->json(['users' => User::all()]);
});

Route::prefix('benchmark')->group(function () {
    Route::get('/users/{iterations?}', [BenchmarkController::class, 'users']);
});
