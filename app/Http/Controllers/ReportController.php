<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Notifications\TelegramNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class ReportController extends Controller
{
    public function createReport(Request $request)
    {
        // If no date is provided, use today's date
        if (!$request->has('date')) {
            $request->merge([
                'date' => Carbon::today()->format('d-m-Y'), // format: DD-MM-YYYY
            ]);
        }

        $validated = $request->validate([
            'department'  => 'required|string|max:255',
            'report_type' => 'required|string|max:255',
            'description' => 'required|string',
            'date'        => 'required|date|after_or_equal:today',
        ]);

        try {
            // ✅ Save to DB
            $report = Report::create([
                'user_id'     => Auth::id(), // logged-in user
                'department'  => $validated['department'],
                'report_type' => $validated['report_type'],
                'description' => $validated['description'],
                'date'        => $validated['date'],
            ]);

            // Decide bot/chat_id based on department
            switch (strtolower($validated['department'])) {
                case 'media':
                    $botToken = config('services.telegram-bot-api.media.bot_token');
                    $chatId   = config('services.telegram-bot-api.media.chat_id');
                    break;
                case 'boost':
                    $botToken = config('services.telegram-bot-api.boost.bot_token');
                    $chatId   = config('services.telegram-bot-api.boost.chat_id');
                    break;
                case 'it':
                    $botToken = config('services.telegram-bot-api.it.bot_token');
                    $chatId   = config('services.telegram-bot-api.it.chat_id');
                    break;
                default:
                    $botToken = config('services.telegram-bot-api.weekly.bot_token');
                    $chatId   = config('services.telegram-bot-api.weekly.chat_id');
                    break;
            }
            // remove all HTML tags from description
            // $validated['description'] = strip_tags($validated['description']);
            $validated['name'] = Auth::id();
            // Send notification without a model
            Notification::route('telegram', [
                'chat_id'   => $chatId,
                'bot_token' => $botToken
            ]) // your Telegram chat ID
                ->notify(new TelegramNotification($validated, $chatId, $botToken));

            return response()->json([
                'success' => true,
                'message' => 'Report sent successfully.',
                'data' => $report
            ], 200);
        } catch (\Throwable $ex) {
            Log::error('Telegram notification failed', [
                'error' => $ex->getMessage(),
                'trace' => $ex->getTraceAsString(),
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to send report.'], 500);
        }
    }

    public function getReport()
    {
        try {
            // ✅ Get only reports for the logged-in user
            // $reports = Report::with('user')
            //     ->orderBy('created_at', 'desc')
            //     ->get();

            $user = Auth::user();

            if ($user->hasRole('admin') || $user->is_admin == 1) {
                // Admin → show all reports
                $reports = Report::with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                // Normal user → show only self
                $reports = Report::with('user')
                    ->where('user_id', $user->id)->get();
            }

            if ($reports->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No reports found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $reports,
            ], 200);
        } catch (\Throwable $ex) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reports',
                'error' => $ex->getMessage(),
            ], 500);
        }
    }
}
