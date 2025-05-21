<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;

    public function __construct($token)
    {
        dd(__LINE__);
        $this->token = $token;
    }

    public function build()
    {
        dd(__LINE__);
        $url = route('register.invited', ['token' => $this->token]);

        return $this->subject('You are invited!')
                    ->markdown('emails.invite')
                    ->with(['url' => $url]);
    }
}
