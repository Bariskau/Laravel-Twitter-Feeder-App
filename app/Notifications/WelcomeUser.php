<?php

namespace App\Notifications;

use App\Channels\EmailChannel;
use App\Channels\SmsChannel;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class WelcomeUser extends Notification
{
    use Queueable;

    protected string $emailOtp;
    protected string $smsOtp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $emailOtp, string $smsOtp)
    {
        $this->emailOtp = $emailOtp;
        $this->smsOtp = $smsOtp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via(): array
    {
        return [SmsChannel::class, EmailChannel::class];
    }

    /**
     * @param $notifiable
     * @return SmsMessage
     */
    public function toEmail($notifiable)
    {
        return (new SmsMessage())
            ->from('twitter app')
            ->to($notifiable->email)
            ->line("Verification Code: " . $this->emailOtp);
    }

    /**
     * @param $notifiable
     * @return SmsMessage
     */
    public function toSms($notifiable)
    {
        return (new SmsMessage())
            ->from('twitter app')
            ->to($notifiable->phone_number)
            ->line("Verification Code: " . $this->smsOtp);
    }

}
