<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user-levels'], function () {
    Route::any('/', "Tugelsikile\\UserLevel\\app\\Controllers\\UserLevelController@crud");
    Route::get('/view');
});
