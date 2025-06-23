<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $notifications;

    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }

    public function build()
    {
        $firstNotification = $this->notifications->first();

        return $this->subject($firstNotification['subject'])
                    ->markdown('emails.notification')
                    ->with([
                        'notifications' => $this->notifications,
                        'appName' => config('app.name'),
                        'appUrl' => config('app.url')
                    ]);
    }
}
