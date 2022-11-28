<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendMailService extends Mailable
{
    public $details;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->details['title'])->html($this->details['body']);;
    }
}
