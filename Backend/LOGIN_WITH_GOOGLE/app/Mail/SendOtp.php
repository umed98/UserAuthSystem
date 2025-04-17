<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $username;

    public function __construct($otp, $username)
    {
        $this->otp = $otp;
        $this->username = $username;
    }

    public function build()
    {

        return $this->subject('Your verification otp')
        ->html(
            "<h2>Your OTP for Account Verification</h2>
            <p>Your OTP is: <strong>{$this->otp}</strong></p> "
        );
    }
}


