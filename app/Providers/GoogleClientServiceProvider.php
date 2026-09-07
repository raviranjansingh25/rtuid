<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class GoogleClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $config = config('google_client');

    $client = new Google_Client();
    $client->setAuthConfig($config['google_client']);
    $client->setScopes($config['scopes']);

    $this->app->instance(Google_Client::class, $client);
    }
}
