<?php

namespace App\Http\Controllers;

use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function sendToTelegram(Request $request)
    {
        // If no date is provided, use today's date
        if (!$request->has('date')) {
            $request->merge([
                'date' => Carbon::today()->format('d-m-Y'), // format: DD-MM-YYYY
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_type' => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date|after_or_equal:today',
        ]);

        try {
            // Decide bot/chat_id based on report_type
            switch (strtolower($validated['report_type'])) {
                case 'weekly':
                    $botToken = config('services.telegram-bot-api.weekly.bot_token');
                    $chatId   = config('services.telegram-bot-api.weekly.chat_id');
                    break;
                case 'monthly':
                    $botToken = config('services.telegram-bot-api.monthly.bot_token');
                    $chatId   = config('services.telegram-bot-api.monthly.chat_id');
                    break;
                default:
                    $botToken = config('services.telegram-bot-api.weekly.bot_token');
                    $chatId   = config('services.telegram-bot-api.weekly.chat_id');
                    break;
            }

            // Send notification without a model
            Notification::route('telegram', [
                'chat_id'   => $chatId,
                'bot_token' => $botToken
            ]) // your Telegram chat ID
                ->notify(new TelegramNotification($validated, $chatId, $botToken));

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
