<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'user-levels'], function () {
    Route::any('/', "Tugelsikile\\UserLevel\\app\\Controllers\\UserLevelController@crud")->name('auth.users.levels');
    Route::get('/view');
});
