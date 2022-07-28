<?php

namespace App\Console\Commands;

use App\Library\Common;
use Illuminate\Console\Command;
use App\Services\CampaignServices;
use App\Services\CheckPointServices;
use App\Console\Commands\Traits\ReminderTrait;

class RemindAssessorCompleteCommand extends Command
{
    use ReminderTrait;

    public $campaignServices;
    public $checkPointServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remind:assessor-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind assessor complete their checkpoint form';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CampaignServices $campaignServices, CheckPointServices $checkPointServices)
    {
        parent::__construct();
        $this->campaignServices = $campaignServices;
        $this->checkPointServices = $checkPointServices;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->remind(Common::REMIND_ASSESSOR_COMPLETE);
    }
}
