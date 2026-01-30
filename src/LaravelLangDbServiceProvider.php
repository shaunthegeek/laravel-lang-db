<?php

namespace Shaunthegeek\LaravelLangDb;

use Illuminate\Support\ServiceProvider;
use Shaunthegeek\LaravelLangDb\Console\Commands\ExportLanguages;
use Shaunthegeek\LaravelLangDb\Console\Commands\ImportLanguages;

class LaravelLangDbServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ExportLanguages::class,
                ImportLanguages::class,
            ]);

            $this->publishes([
                __DIR__.'/../database/migrations/create_languages_table.php.stub' => database_path('migrations/'.date('Y_m_d_His', time()).'_create_languages_table.php'),
            ], 'laravel-lang-db-migrations');
        }
    }
}
