<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProfileUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $changedData;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $changedData)
    {
        $this->user = $user;
        $this->changedData = $changedData;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('emails.profile_updated')
                    ->subject('Your Profile Has Been Updated')
                    ->with([
                        'user' => $this->user,
                        'changedData' => $this->changedData,
                    ]);
    }
}
