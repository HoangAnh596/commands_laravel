<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\RemindAssessorCompleteEmail;

class RemindAssessorComplete extends Job
{
    public $assessors;
    public $currentCampaign;
    public $remainingDay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($assessors, $currentCampaign, $remainingDay)
    {
        $this->assessors = $assessors;
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
        foreach ($this->assessors as $assessor) {
            Mail::to($assessor->email)->send(
                new RemindAssessorCompleteEmail($assessor, $this->currentCampaign, $this->remainingDay)
            );
        }
    }
}
