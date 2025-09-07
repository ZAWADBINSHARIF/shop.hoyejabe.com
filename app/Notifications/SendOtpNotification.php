<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsqChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SendOtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $otp;
    protected $purpose;

    /**
     * Create a new notification instance.
     *
     * @param string $otp
     * @param string $purpose
     */
    public function __construct(string $otp, string $purpose = 'verification')
    {
        $this->otp = $otp;
        $this->purpose = $purpose;
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
        $appName = config('app.name');

        $messages = [
            'verification' => "Your {$appName} verification code is: {$this->otp}. Valid for 5 minutes. Do not share with anyone.",
            'signup' => "Your {$appName} signup OTP is: {$this->otp}. Valid for 5 minutes.",
            'password_reset' => "Your {$appName} password reset code is: {$this->otp}. Valid for 10 minutes.",
            'order_confirmation' => "Your {$appName} order confirmation code is: {$this->otp}. Valid for 15 minutes.",
        ];

        return [
            'message' => $messages[$this->purpose] ?? $messages['verification'],
            'sender_id' => config('services.smsq.sender_id', $appName),
            'is_flash' => false,
            'template_id' => $this->getTemplateId()
        ];
    }

    /**
     * Get template ID based on purpose
     *
     * @return string|null
     */
    protected function getTemplateId(): ?string
    {
        $templates = config('services.smsq.templates', []);

        return $templates[$this->purpose] ?? null;
    }
}
