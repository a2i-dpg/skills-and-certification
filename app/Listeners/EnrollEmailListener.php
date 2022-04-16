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
        $array['view'] = $event->email_data['view'];
        $array['subject'] = $event->email_data['subject'];
        $array['from'] = env('MAIL_FROM_ADDRESS') ?? $event->email_data['from'];
        $array['email_data'] = $event->email_data;
        try {
            Mail::to($event->email_data['email'])->queue(new TraineeEnrollEmail($array));
        } catch (\Exception $e) {

        }
    }
}
