<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserAccountDisabledNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        return $this->view('emails.user_account_disabled')
                    ->with([
                        'userEmail' => $this->user->email,
                    ]);
    }
}
