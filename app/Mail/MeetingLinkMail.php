<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $teacherName;
    public $meetingLink;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($teacherName, $meetingLink)
    {
        $this->teacherName = $teacherName;
        $this->meetingLink = $meetingLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Zoom Meeting Link for Your Session')
                    ->view('emails.meeting_link');  // View template for the email
    }
}
