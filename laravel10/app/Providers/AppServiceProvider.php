<?php

namespace App\Providers;

use App\Models\AppSettings;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (\Schema::hasTable('app_settings')) {
            $data = AppSettings::get()->mapWithKeys(function($item) {
                return [ $item['name'] => $item['value'] ];
            })->toArray();

            config($data);
            // dd($data);
            // \App::setLocale(config('app.locale'));
        }
    }
}
