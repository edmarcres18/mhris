<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\QuotationRequest;

class QuotationStatusUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $quotation;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new message instance.
     */
    public function __construct(QuotationRequest $quotation, $oldStatus, $newStatus)
    {
        $this->quotation = $quotation;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Quotation Request Status Has Been Updated')
            ->markdown('emails.quotation-status-update')
            ->with([
                'quotation' => $this->quotation,
                'oldStatus' => ucfirst($this->oldStatus),
                'newStatus' => ucfirst($this->newStatus),
                'notes' => $this->quotation->notes
            ]);
    }
} 