<?php

use crocodicstudio\crudbooster\helpers\CBRouter;
use crocodicstudio\crudbooster\middlewares\CBAuthAPI;


CBRouter::route();
Route::group(['middleware' => ['api', CBAuthAPI::class], 'namespace' => 'App\Http\Controllers'], function () {

    Route::get('{url}', [App\Http\Controllers\LandingPagesController::class, 'catchView']);
});
