<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    /**
     * إنشاء رسالة البريد الإلكتروني
     *
     * @param int $otp
     * @return void
     */
    public function __construct($otp)
    {
        $this->otp = $otp;  // تخزين OTP ليتم عرضه في العرض
    }

    /**
     * بناء الرسالة.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Your OTP Code')  // تعيين موضوع الرسالة
                    ->view('emails.otp');     // تحديد العرض لعرض OTP
    }
}
