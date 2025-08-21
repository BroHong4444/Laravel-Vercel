<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class TelegramNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $data;
    protected $chatId;
    protected $botToken;
    public function __construct(array $data, $chatId, $botToken)
    {
        $this->data = $data;
        $this->chatId = $chatId;
        $this->botToken = $botToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toTelegram($notifiable)
    {
        // $chatId = config('services.telegram-bot-api.weekly_chat_id');

        return TelegramMessage::create()
            ->to($this->chatId)
            ->token($this->botToken) // important for multiple bots
            ->content(
                "🔔 <b>New Report Received</b>\n\n" .
                    "👤 <b>Employee Name:</b> " . Auth()->user()->name . "\n\n" .
                    "🏢 <b>Department:</b> {$this->data['department']}\n\n" .
                    "📝 <b>Report Type:</b> {$this->data['report_type']}\n\n" .
                    "📅 <b>Date:</b> {$this->data['date']}\n\n" .
                    "🗒️ <b>Description:</b>\n\n{$this->data['description']}"
            )
            ->options(['parse_mode' => 'HTML']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
