<?php

namespace App\Console\Commands;

use App\Library\Common;
use Illuminate\Console\Command;
use App\Services\CampaignServices;
use App\Services\CheckPointServices;
use App\Console\Commands\Traits\NotifyTrait;

class NotifyListEmpCompletingForm extends Command
{
    use NotifyTrait;

    public $campaignServices;
    public $checkPointServices;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:emp-completing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify list employee completing form to assessor';

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
        $this->notify(Common::LIST_EMP_COMPLETING_FORM);
    }
}
