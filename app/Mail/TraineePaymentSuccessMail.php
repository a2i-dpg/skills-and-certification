<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TraineePaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public string $msg;
    public string $accessKey;
    public string $youthName;

    public function __construct($subject, $accessKey, $msg, $youthName)
    {
        $this->accessKey = $accessKey;
        $this->msg = $msg;
        $this->subject = $subject;
        $this->youthName = $youthName;
    }



    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): TraineePaymentSuccessMail
    {
        return $this->from(env('MAIL_FROM_ADDRESS'))
            ->subject($this->subject)
            ->view('frontend.email.youth-payment-success');
    }
}
