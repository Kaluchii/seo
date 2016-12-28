<?php

namespace Interpro\Seo;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SeoFirstServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot(Dispatcher $dispatcher)
    {
        Log::info('Загрузка SeoFirstServiceProvider');

        $this->publishes([__DIR__.'/config/seo.php' => config_path('interpro/seo.php')]);

        $this->publishes([
            __DIR__.'/migrations' => database_path('migrations')
        ], 'migrations');
    }

    /**
     * @return void
     */
    public function register()
    {
        Log::info('Регистрация SeoFirstServiceProvider');

        $forecastList = $this->app->make('Interpro\Core\Contracts\Taxonomy\TypesForecastList');

        $forecastList->registerCTypeName('seo');
    }

}
