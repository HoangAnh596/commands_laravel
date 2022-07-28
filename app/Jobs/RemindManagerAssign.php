<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Mail;
use App\Mail\RemindingManagerAssignEmail;

class RemindManagerAssign extends Job
{
    public $users;
    public $currentCampaign;
    public $remainingDay;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($users, $currentCampaign, $remainingDay)
    {
        $this->users = $users;
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
        foreach ($this->users as $user) {
            Mail::to($user->email)->send(
                new RemindingManagerAssignEmail($user, $this->currentCampaign, $this->remainingDay)
            );
        }
    }
}
