<?php

use App\Http\Controllers\BenchmarkController;
use App\Http\Controllers\ReportController;
use App\Models\User;
use App\Notifications\TelegramNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
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

Route::post('/send-report', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'report_type' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    try {
        $chatId = config('services.telegram-bot-api.chat_id'); // Load from config

        // Send notification without a notifiable model
        Notification::route('telegram', $chatId)
            ->notify(new TelegramNotification($validated));

        return response()->json(['success' => true, 'message' => 'Report sent successfully.'], 200);
    } catch (\Throwable $ex) {
        Log::error('Telegram notification failed', [
            'error' => $ex->getMessage(),
            'trace' => $ex->getTraceAsString(),
        ]);

        return response()->json(['success' => false, 'message' => 'Failed to send report.', 'error' => $ex->getMessage()], 500);
    }
});

// Test route
Route::post('/post-method', function () {
    return ['message' => 'This is post method'];
});
