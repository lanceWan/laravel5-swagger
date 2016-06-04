<?php

namespace Iwanli\Swagger;

use Illuminate\Support\ServiceProvider;

class SwaggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/swagger.php' => config_path('swagger.php'),
        ]);
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/swagger'),
        ], 'public');
        $this->loadViewsFrom(__DIR__.'/views', 'swagger');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/swagger'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        require_once __DIR__ .'/routes.php';
    }

}
