<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsqChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendSmsNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;
    protected $senderId;
    protected $isFlash;
    protected $templateId;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @param string|null $senderId
     * @param bool $isFlash
     * @param string|null $templateId
     */
    public function __construct(
        string $message, 
        ?string $senderId = null, 
        bool $isFlash = false,
        ?string $templateId = null
    ) {
        $this->message = $message;
        $this->senderId = $senderId;
        $this->isFlash = $isFlash;
        $this->templateId = $templateId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsqChannel::class];
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toSmsq($notifiable)
    {
        return [
            'message' => $this->message,
            'sender_id' => $this->senderId ?? config('services.smsq.sender_id'),
            'is_flash' => $this->isFlash,
            'template_id' => $this->templateId
        ];
    }
}