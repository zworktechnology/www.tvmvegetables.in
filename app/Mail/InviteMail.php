<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invite;

class InviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    public function build()
    {
        return $this->subject('Invite Mail From Zwork Technology POS - Invite you to access')
                ->view('email.invite');
    }
}
