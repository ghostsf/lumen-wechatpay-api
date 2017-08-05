<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return "Hello ghostsf";
});

$app->get('demo', 'TestController@demo');

$app->group(['prefix' => 'wepayapi/v1'], function ($app) {

    $app->post('createOrder4JSBridge,', 'WePayController@createOrderJSBridge');
    $app->post('createOrder4JSSDK,', 'WePayController@createOrderJSSDK');
    $app->post('createOrder4APP', 'WePayController@createOrderAPP');

    $app->post('notify', 'WePayController@notifyUrl');

    $app->get('test', 'WePayController@test');
});
