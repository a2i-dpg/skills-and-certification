<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use \Illuminate\Auth\Listeners\SendEmailVerificationNotification as SendEmailVerificationNotificationDublicate;

class SendEmailVerificationNotification extends SendEmailVerificationNotificationDublicate implements ShouldQueue
{
    use Queueable;
}
