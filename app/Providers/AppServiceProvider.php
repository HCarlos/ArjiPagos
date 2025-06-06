<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void{
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void{
        Vite::prefetch(concurrency: 30);

//        Validator::extend('username', function ($attribute, $value, $parameters, $validator) {
//            // Lógica de validación, ejemplo:
//            return preg_match('/^[a-zA-Z0-9_\-]+$/', $value);
//        });
//        Validator::replacer('username', function ($message, $attribute, $rule, $parameters) {
//            return "El formato del $attribute es inválido";
//        });

//        DB::statement("CREATE TEXT SEARCH CONFIGURATION es (COPY = spanish)");
//        DB::statement("ALTER TEXT SEARCH CONFIGURATION es ALTER MAPPING FOR hword, word WITH unaccent, spanish_stem");

        if($this->app->environment('production')) {
            URL::forceScheme('https');
        }



    }
}
