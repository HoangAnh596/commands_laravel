<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\RemindManagerApprovalEmail;

class RemindManagerApproval extends Job
{
    public $managers;
    public $currentCampaign;
    public $remainingDay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($managers, $currentCampaign, $remainingDay)
    {
        $this->managers = $managers;
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
        foreach ($this->managers as $manager) {
            Mail::to($manager->email)->send(
                new RemindManagerApprovalEmail($manager, $this->currentCampaign, $this->remainingDay)
            );
        }
    }
}
