<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $companyDetails;

    public function __construct($data)
    {
        $this->data = $data;
        $this->companyDetails = [
            'name' => 'Medical & Hospital Resources Health Care, Inc.',
            'address' => 'MHR Building: Jose L. Briones St., NRA, Cebu City, Philippines, 6000',
            'phone' => '+63 32 234 5678',
            'email' => 'csr.mhrhealthcare@gmail.com',
            'website' => 'https://mhrpci.site/mhrhealthcareinc'
        ];
    }

    public function build()
    {
        return $this->subject('New Inquiry from ' . $this->data['name'])
                    ->view('emails.contact-form');
    }
} 