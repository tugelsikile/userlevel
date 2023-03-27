<?php

use Illuminate\Support\Facades\Route;

Route::get('/user-levels/{what}', "Tugelsikile\\UserLevel\\Controllers\\UserLevelController@userLevel");
