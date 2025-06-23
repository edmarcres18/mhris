<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\QuotationRequest;

class QuotationRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotationRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(QuotationRequest $quotationRequest)
    {
        $this->quotationRequest = $quotationRequest;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Quotation Request')
            ->markdown('emails.quotation-request')
            ->with([
                'productName' => $this->quotationRequest->product_name,
                'customerName' => $this->quotationRequest->name,
                'customerEmail' => $this->quotationRequest->email,
                'customerPhone' => $this->quotationRequest->phone,
                'hospitalName' => $this->quotationRequest->hospital_name
            ]);
    }
} 