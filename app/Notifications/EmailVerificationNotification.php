<?php

namespace App\Notifications;

use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

// class EmailVerificationNotification extends Notification implements ShouldQueue
class EmailVerificationNotification extends Notification
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
    public function __construct($fname)
    {
        $this->fname = $fname;
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
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        $statefulURL = str_replace($_ENV['APP_URL'], $_ENV['CURRENT_SANCTUM_STATEFUL_DOMAINS'], $verifyUrl);
        return (new MailMessage)
                    ->subject('Verify Email Address')
                    ->markdown('Email.confirm-email', ['url' => $statefulURL, 'fname' => $this->fname]);
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
