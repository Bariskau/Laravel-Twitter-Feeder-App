<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;

class EmailChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toEmail($notifiable);
        $message->send();
    }
}
