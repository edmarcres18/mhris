<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMailBgpdi extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $companyDetails;

    public function __construct($data)
    {
        $this->data = $data;
        $this->companyDetails = [
            'name' => 'Bay Gas Petroleum Distribution Inc.',
            'address' => 'National Rd. Cansaga, Consolacion, Cebu',
            'phone' => '(032) 419-1014',
            'email' => 'baygaspdi@mhrpci.ph',
            'website' => 'https://mhrpci.site/baygaspetroleumdistributioninc'
        ];
    }

    public function build()
    {
        return $this->subject('New Inquiry from ' . $this->data['name'])
                    ->view('emails.contact-form-bgpdi');
    }
} 