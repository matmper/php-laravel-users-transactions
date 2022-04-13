<?php

namespace App\Providers;

use Validator;
use App\Rules\DocumentNumber;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('document_number', function ($attribute, $value, $params) {
            $rule = new DocumentNumber($params[0] ?? 'both');
            return $rule->passes($attribute, $value);
        });
    }
}
