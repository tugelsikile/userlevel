<?php

namespace Tugelsikile\UserLevel;

use Illuminate\Support\ServiceProvider;

class UserLevel extends ServiceProvider
{
    public function boot(){
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
    }
    public function register()
    {
        
        $this->app->make("Tugelsikile\UserLevel\Controllers\UserLevelController");
    }
}
