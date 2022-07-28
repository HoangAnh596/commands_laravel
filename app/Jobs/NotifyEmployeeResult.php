<?php

namespace App\Jobs;

use App\Library\Common;
use App\Models\CheckPoint;
use App\Mail\EmployeeResultEmail;
use Illuminate\Support\Facades\Mail;

class NotifyEmployeeResult extends Job
{
    public $checkPoint;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(CheckPoint $checkPoint)
    {
        $this->checkPoint = $checkPoint;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config('app.mode') == Common::MODE_STG) {
            $this->checkPoint->employee->email = config('app.mail_test');
        }
        Mail::to($this->checkPoint->employee->email)->send(new EmployeeResultEmail($this->checkPoint));
    }
}
