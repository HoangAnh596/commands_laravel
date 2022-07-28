<?php
// @codingStandardsIgnoreLine
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Campaign;

/**
 * @SuppressWarnings(PHPMD)
 * Class CheckPointProvider
 * @package App\Providers
 */
class CheckPointProvider extends ServiceProvider
{
    public function register()
    {
        // Repositories
        $this->app->singleton("App\Repositories\BaseRepository", "App\Repositories\Impl\BaseRepositoryImpl");
        $this->app->singleton("App\Repositories\CampaignRepository", "App\Repositories\Impl\CampaignRepositoryImpl");
        $this->app->singleton("App\Repositories\UserRepository", "App\Repositories\Impl\UserRepositoryImpl");
        $this->app->singleton(
            "App\Repositories\DepartmentRepository",
            "App\Repositories\Impl\DepartmentRepositoryImpl"
        );
        $this->app->singleton(
            "App\Repositories\DepartmentManagerRepository",
            "App\Repositories\Impl\DepartmentManagerRepositoryImpl"
        );
        $this->app->singleton(
            "App\Repositories\CheckPointRepository",
            "App\Repositories\Impl\CheckPointRepositoryImpl"
        );
        $this->app->singleton(
            "App\Repositories\EmployeePlanRepository",
            "App\Repositories\Impl\EmployeePlanRepositoryImpl"
        );
        $this->app->singleton(
            "App\Repositories\EmployeeTrainingRepository",
            "App\Repositories\Impl\EmployeeTrainingRepositoryImpl"
        );

        // Services
        $this->app->singleton("App\Services\BaseServices", "App\Services\Impl\BaseServicesImpl");
        $this->app->singleton("App\Services\CampaignServices", "App\Services\Impl\CampaignServicesImpl");
        $this->app->singleton("App\Services\UserServices", "App\Services\Impl\UserServicesImpl");
        $this->app->singleton("App\Services\DepartmentServices", "App\Services\Impl\DepartmentServicesImpl");
        $this->app->singleton(
            "App\Services\DepartmentManagerServices",
            "App\Services\Impl\DepartmentManagerServicesImpl"
        );
        $this->app->singleton(
            "App\Services\CheckPointServices",
            "App\Services\Impl\CheckPointServicesImpl"
        );
        $this->app->singleton(
            "App\Services\ReportServices",
            "App\Services\Impl\ReportServicesImpl"
        );
        $this->app->singleton(
            "App\Services\EmployeePlanServices",
            "App\Services\Impl\EmployeePlanServicesImpl"
        );
        $this->app->singleton(
            "App\Services\EmployeeTrainingServices",
            "App\Services\Impl\EmployeeTrainingServicesImpl"
        );
    }

    public function boot()
    {
        Campaign::creating(function ($campaign) {
            $user = auth()->user();
            $campaign->created_by = $user->username;
            $campaign->modified_by = $user->username;
        });

        Campaign::updating(function ($campaign) {
            $user = auth()->user();
            $campaign->modified_by = $user->username;
        });
    }
}
