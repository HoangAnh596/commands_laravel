<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\RemindEmpCompleteEmail;

class RemindEmpComplete extends Job
{
    public $employees;
    public $currentCampaign;
    public $remainingDay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($employees, $currentCampaign, $remainingDay)
    {
        $this->employees = $employees;
        $this->currentCampaign = $currentCampaign;
        $this->remainingDay = $remainingDay;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->employees as $employee) {
            Mail::to($employee->email)->send(
                new RemindEmpCompleteEmail($employee, $this->currentCampaign, $this->remainingDay)
            );
        }
    }
}
