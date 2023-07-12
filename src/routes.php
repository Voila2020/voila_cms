<?php

use crocodicstudio\crudbooster\helpers\CBRouter;
use crocodicstudio\crudbooster\middlewares\CBBackend;


CBRouter::route();
Route::group(['middleware' => ['web', CBBackend::class], 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('{url}', [App\Http\Controllers\LandingPagesController::class, 'catchView']);
});
