<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/order/{orderID}', function (Request $request, Response $response, $args) {
    $discounts = new Discounts();

    $order = @file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/example-orders/order'.preg_replace("/[^0-9\s]/", "",$args['orderID']).'.json');
    
    if($order==false){
        $response = $response->withJson(array("error"=>"Unable to find order ".$args['orderID']), 404);
        $response = $response->withHeader('Content-Type', 'application/json');
    }else{
        $result = $discounts->processCart(json_decode($order,true));
        $response = $response->withJson($result, (isset($result['discountError']))?500:200);
        $response = $response->withHeader('Content-Type', 'application/json');
     } 
     return $response;    
});

$app->post('/order', function(Request $request, Response $response, $args){  
    $discounts = new Discounts();
    $result = $discounts->processCart(json_decode($request->getBody(),true));
    $response = $response->withJson($result, (isset($result['discountError']))?500:200);
    $response = $response->withHeader('Content-Type', 'application/json');
    return $response;    
});

$app->get('/order', function(Request $request, Response $response, $args){
    $response = $response->withJson(array("error"=>"You must POST order data to this end point."), 500);
    $response = $response->withHeader('Content-Type', 'application/json');
    return $response;    
});

$app->get('/', function(Request $request, Response $response, $args){
    $response = $response->withJson(array("error"=>"End point not found"), 404);
    $response = $response->withHeader('Content-Type', 'application/json');
    return $response;    
});