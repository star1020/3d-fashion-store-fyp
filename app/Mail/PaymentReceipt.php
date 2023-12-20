<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceipt extends Mailable
{
    use Queueable, SerializesModels;
    public $eReceiptDetails;
    /**
     * Create a new message instance.
     */
    public function __construct($eReceiptDetails)
    {
        $this->eReceiptDetails = $eReceiptDetails; // Assign passed details to the property
    }
    public function build()
    {
        return $this->subject('Payment Receipt')
                    ->view('user.payment-receipt')
                    ->with('details', $this->eReceiptDetails);
    }
    
}
