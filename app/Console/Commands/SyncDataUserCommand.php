<?php

namespace App\Console\Commands;

use App\Services\UserServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncDataUserCommand extends Command
{
    protected $userService;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-data/member';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data members from system hrm';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserServices $userServices)
    {
        $this->userService = $userServices;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
//        Log::info('START SYNC DATA MEMBER');
        $this->userService->syncAllData();
        echo('Sync data member successfully');
    }
}
