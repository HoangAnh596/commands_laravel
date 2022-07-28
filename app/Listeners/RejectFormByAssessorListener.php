<?php

namespace App\Listeners;

use App\Library\Common;
use App\Mail\AssessorRejectEmail;
use Illuminate\Support\Facades\Mail;
use App\Events\RejectFormByAssessor;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RejectFormByAssessorListener implements ShouldQueue
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
     * @param  \App\Events\RejectFormByAssessor  $event
     * @return void
     */
    public function handle(RejectFormByAssessor $event)
    {
        $checkPoint = $event->checkPoint;
        if (config('app.mode') == Common::MODE_STG) {
            $checkPoint->employee->email = config('app.mail_test');
        }
        Mail::to($checkPoint->employee->email)->send(new AssessorRejectEmail($checkPoint));
    }
}
