<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite; 

    public function __construct($invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        return $this->subject('You are invited!')
                    ->view('emails.invite')
                    ->with(['invite' => $this->invite]);
    }
}
