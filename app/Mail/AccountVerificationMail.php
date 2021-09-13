<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountVerificationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $verificationDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($verificationDetails)
    {
        $this->verificationDetails = $verificationDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.AccountVerification')->with('verificationDetails', $this->verificationDetails);
    }
}
