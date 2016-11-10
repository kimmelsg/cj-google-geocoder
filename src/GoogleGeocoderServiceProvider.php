<?php

namespace Spatie\Geocoder;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use NavJobs\GoogleGeocoder\GoogleGeocoder;

class GoogleGeocoderServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../../config/geocoder.php' => config_path('geocoder.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../../config/geocoder.php', 'geocoder');

        $this->app->bind(GoogleGeocoder::class, function ($app) {
            $client = new Client();
            return new GoogleGeocoder(
                $client,
                config('geocoder.key'),
                config('geocoder.language'),
                config('geocoder.region')
            );
        });
    }
}