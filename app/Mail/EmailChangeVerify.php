<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailChangeVerify extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public $encrypted_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $encrypted_email)
    {
        $this->user = $user;
        $this->encrypted_email = $encrypted_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.email-change-confirm', [
            'user' => $this->user,
            'encrypted_email' => $this->encrypted_email
        ]);
    }
}
