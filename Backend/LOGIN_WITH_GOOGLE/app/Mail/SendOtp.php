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
        ->html("
    <div style='font-family: Arial, sans-serif;'>
        <h2>Hello {$this->username},</h2>
        <p>Thank you for signing up. Your OTP is:</p>
        <p style='font-size: 24px; font-weight: bold; color: #2d3748;'>{$this->otp}</p>
        <p>This OTP is valid for 30 minutes.</p>
        <p>– Fastranking.tech Team</p>
    </div>
");
    }
}
