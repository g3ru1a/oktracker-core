<?php

namespace App\Providers;

use App\Mail\AuthMailInterface;
use App\Mail\AuthMail;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
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
        $this->app->bind(AuthMailInterface::class, AuthMail::class);
    }
}
