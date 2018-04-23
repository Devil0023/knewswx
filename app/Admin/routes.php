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

    $router->resource("/exchange", ExchangeController::class);

    $router->resource('/points/rule', PointsruleController::class);

    $router->resource("/points/log", PointslogController::class);

    $router->resource('/wxuser', WxuserController::class);

    $router->resource('/wxmenu', WxmenuController::class);

    $router->resource('/questionnaire', QuestionnaireController::class);

    $router->resource("/questionnaire/{qid}/questioninfo", QuestioninfoController::class);

    $router->resource("/questionnaire/{qid}/surveyresult", SurveyresultController::class);

});
