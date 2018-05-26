<?php

namespace App\Providers;

use App\MailChimp\ExportSubscriber;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindMailChimp();
    }



    protected function bindMailChimp()
    {
        $this->app->singleton(ExportSubscriber::class, function ($app) {
            return new ExportSubscriber(
                config('mailchimp.api_key'),
                config('mailchimp.api_url'),
                app(\Nathanmac\Utilities\Parser\Parser::class),
                app(\GuzzleHttp\Client::class)
            );
        });
    }
}
