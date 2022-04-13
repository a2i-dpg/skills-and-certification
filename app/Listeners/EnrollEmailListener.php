<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Mail\TraineeEnrollEmail;
use Mail;

class EnrollEmailListener implements ShouldQueue
{
    
    public function handle($event)
    {
        //sleep(10);
        //$array['view'] = 'emails.invoice';
        $array['subject'] = 'Enroll confirmation email';
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['trainee'] = $event->trainee;
        try {
            Mail::to($event->trainee['email'])->queue(new TraineeEnrollEmail($array));
            //Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new TraineeEnrollEmail($array));

        } catch (\Exception $e) {

        }
    }
}
