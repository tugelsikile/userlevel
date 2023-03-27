<?php

namespace Tugelsikile\UserLevel;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Console\Output\ConsoleOutput;
use Tugelsikile\UserLevel\app\Middleware\UserLevelMiddleware;

class UserLevel extends ServiceProvider
{
    public function boot(Router $router){
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $router->middlewareGroup('user-level',[
            UserLevelMiddleware::class,
        ]);
    }
    public function register()
    {
        $this->app->make("Tugelsikile\UserLevel\app\Controllers\UserLevelController");
        $this->publishes([
            __DIR__ . '/databases/migrations' => database_path('migrations')
        ], 'migrations');
        /*$this->publishes([
            __DIR__ . '/databases/seeders' => database_path('seeders')
        ], 'seeders');*/
        $this->publishes([
            __DIR__ . '/config/menus.php' => config_path('menus.php')
        ], 'config');
    }

}
