<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Career;
use App\Models\Hiring;

class ApplicationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $hiringDetails;

    public function __construct(Career $application, Hiring $hiringDetails)
    {
        $this->application = $application;
        $this->hiringDetails = $hiringDetails;
    }

    public function build()
    {
        return $this->view('emails.application-confirmation')
                    ->subject('Application Confirmation - ' . $this->hiringDetails->position);
    }
}
