<?php

namespace App\Providers;

use Exception;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CheckPoint;
use App\Policies\CampaignPolicy;
use App\Policies\CheckPointPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     * @SuppressWarnings("unused")
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::policy(CheckPoint::class, CheckPointPolicy::class);

        $this->app['auth']->viaRequest('api', function ($request) {
            try {
                $token = !empty($request->get('access-token')) ?
                    $request->get('access-token') :
                    $request->bearerToken();
                $tokenParts = explode(".", $token);
                $tokenPayload = str_replace('-', '+', $tokenParts[1]);
                $tokenPayload = str_replace('_', '/', $tokenPayload);
                $tokenPayload = base64_decode($tokenPayload);
                $jwtPayload = json_decode($tokenPayload);
                $user = User::find($jwtPayload->data->id) ?? null;
                return $user;
            } catch (Exception $e) {
                \Log::error($e);
                return null;
            }
        });
    }
}
