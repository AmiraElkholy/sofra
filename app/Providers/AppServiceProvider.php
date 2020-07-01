<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Validator;


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
        //Add custom validation for offer price (less_than_field) rule 
        Validator::extend('less_than_or_equal_field', function($attribute, $value, $parameters, $validator) {
            $max_field = $parameters[0];
            $data = $validator->getData();
            $max_value = $data[$max_field];
            return $value <= $max_value;
        });   

        Validator::replacer('less_than_or_equal_field', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
        });


    }
}
