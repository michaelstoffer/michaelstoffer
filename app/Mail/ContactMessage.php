<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public array $data;
    public bool $copy;

    public function __construct(array $data, bool $copy = false)
    {
        $this->data = $data;
        $this->copy = $copy;
        $subject = $copy ? 'Copy of your message to Michael Stoffer' : 'New message via michaelstoffer.com';
        $this->subject($subject);
    }

    public function build()
    {
        return $this->markdown('mail.contact');
    }
}
