<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user,$password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password=$password;
    }

    public function build()
    {
        return $this->view('emails.forget_password_notification')
                    ->subject('Cập nhật mật khẩu mới')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password
                    ]);
    }
}
