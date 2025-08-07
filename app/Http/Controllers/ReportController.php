<?php

namespace App\Http\Controllers;

use App\Notifications\TelegramNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    // public function sendToTelegram(Request $request)
    // {
    //     $validated = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'report_type' => 'required|string|max:255',
    //         'description' => 'required|string',
    //     ]);

    //     try {
    //         $chatId = config('services.telegram-bot-api.chat_id'); // Load from config
    //         // Send notification without a model
    //         Notification::route('telegram', $chatId) // your Telegram chat ID
    //             ->notify(new TelegramNotification($validated));

    //         return response()->json(['success' => true, 'message' => 'Report sent successfully.'], 200);
    //     } catch (\Throwable $ex) {
    //         Log::error('Telegram notification failed', [
    //             'error' => $ex->getMessage(),
    //             'trace' => $ex->getTraceAsString(),
    //         ]);

    //         return response()->json(['success' => false, 'message' => 'Failed to send report.'], 500);
    //     }
    // }

    public function sendToTelegram(Request $request)
    {
        $validated = validator($request->query(), [
            'name' => 'required|string|max:255',
            'report_type' => 'required|string|max:255',
            'description' => 'required|string',
        ])->validate();

        try {
            $chatId = config('services.telegram-bot-api.chat_id');

            Notification::route('telegram', $chatId)
                ->notify(new TelegramNotification($validated));

            return response()->json(['success' => true, 'message' => 'Report sent successfully.'], 200);
        } catch (\Throwable $ex) {
            Log::error('Telegram notification failed', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to send report.'], 500);
        }
    }
}
