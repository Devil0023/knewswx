<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('/prize', PrizeController::class);
    $router->get("/prize/rsync/{id}", "PrizeController@rsync");

    $router->resource('/points/rule', PointsruleController::class);
    $router->resource('/wxuser', WxuserController::class);

});
