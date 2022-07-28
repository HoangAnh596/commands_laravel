<?php

namespace App\Listeners;

use App\Library\Common;
use App\Mail\ManagerRejectEmail;
use App\Events\RejectFormByManager;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectFormByManagerListener implements ShouldQueue
{

    use InteractsWithQueue;

    public $connection = 'database';

    public $delay = 30;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\RejectFormByManager  $event
     * @return void
     */
    public function handle(RejectFormByManager $event)
    {
        $checkPoint = $event->checkPoint;
        if (config('app.mode') == Common::MODE_STG) {
            $checkPoint->assessor->email = config('app.mail_test');
        }
        Mail::to($checkPoint->assessor->email)->send(new ManagerRejectEmail($checkPoint));
    }
}
