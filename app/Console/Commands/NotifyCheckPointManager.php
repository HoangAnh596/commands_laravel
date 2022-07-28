<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Library\Common;
use Illuminate\Console\Command;
use App\Services\CampaignServices;
use App\Jobs\SendEmailImportEmployee;

class NotifyCheckPointManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:cp-manager {id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify checkpoint to managers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $id = $this->argument('id');
        if (!$id) {
            echo("Have no manager_id");
        } else {
            $managers = [];
            $usersId = explode(',', $id);
            $users = User::whereIn('id', $usersId)->get();
            foreach ($users as $user) {
                if (config('app.mode') == Common::MODE_STG) {
                    $user->email = config('app.mail_test');
                }

                if (!in_array($user, $managers)) {
                    $managers[] = $user;
                }
            }
            $campaignServices = app(CampaignServices::class);
            $campaign = $campaignServices->getCurrentCampaign();
            dispatch((new SendEmailImportEmployee($managers, $campaign))
                        ->delay(Carbon::now()->addSeconds(30)));
            echo("Notify checkpoint to managers");
        }
    }
}
