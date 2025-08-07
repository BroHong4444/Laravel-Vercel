<?php

use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\ReportController;
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

Route::get('/send-report', [ReportController::class, 'sendToTelegram']);

Route::post('/post-method', function () {
    return ['message' => 'This is post method'];
});

Route::post('/test-post', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'POST request received!',
        'data' => $request->all()
    ]);
});
