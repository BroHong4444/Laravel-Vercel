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
    protected $data;
    protected $chatId;
    protected $botToken;
    public function __construct(array $data, $chatId, $botToken)
    {
        $this->data = $data;
        $this->chatId = $chatId;
        $this->botToken = $botToken;
    }

    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        return TelegramMessage::create()
            ->to($this->chatId)
            ->token($this->botToken) // important for multiple bots
            ->content(
                "🔔 <b>New Report Received</b>\n\n" .
                    "<b>+ Employee Name:</b> " . Auth()->user()->name . "\n" .
                    "<b>+ Department:</b> {$this->data['department']}\n" .
                    "<b>+ Report Type:</b> {$this->data['report_type']}\n" .
                    "<b>+ Date:</b> {$this->data['date']}\n" .
                    "<b>+ Description:</b>\n\n{$this->data['description']}"
            )
            ->options(['parse_mode' => 'HTML']);
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
