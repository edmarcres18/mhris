<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Career;

class InterviewScheduled extends Mailable
{
    use Queueable, SerializesModels;

    public $career;

    public function __construct(Career $career)
    {
        $this->career = $career;
    }

    public function build()
    {
        return $this->view('emails.interview-scheduled')
                    ->subject('Interview Scheduled - ' . $this->career->hiring->position);
    }
}
