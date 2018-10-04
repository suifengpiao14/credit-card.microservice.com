<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018\8\4 0004
 * Time: 15:36.
 */
use Slim\Http\Request;
use Slim\Http\Response;

/*
 * 文档html页面
 */
$app->get('/', function (Request $request, Response $response, $arguments = []) {
    return $response->withRedirect('/swagger-ui', 301);
});



$app->get('/api/v1/credit/which-card', 'App\\Controllers\\Api\\v1\\CreditController:whichCard')->setName('which_card');
$app->get('/api/v1/credit/which-card-and-date', 'App\\Controllers\\Api\\v1\\CreditController:whichCardAndDate')->setName('which_card_and_date');

/*
 *  文档json地址
 */
$app->get('/swagger/json', 'App\\Controllers\\SwaggerController:json')->setName('swagger_json');
