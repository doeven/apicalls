<?php

namespace App\Notifications;

use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use App\Http\Controllers\MailController;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

// class CustomResetPassword extends Notification implements ShouldQueue
class CustomResetPassword extends Notification
{
    use Queueable;
    public $token;
    public $fname;
    public $email;
    public $mailData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $fname, $email)
    {
        $this->token = $token;
        $this->fname = $fname;
        $this->email = $email;
        $this->mailData = [$token, $fname, $email];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Password Reset')
                    ->markdown('Email.password-reset', ['token' => $this->token, 'fname' => $this->fname, 'email' => $this->email]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
