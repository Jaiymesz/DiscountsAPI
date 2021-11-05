<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

$app->get('/order/{orderID}', function (Request $request, Response $response, $args) {
    $discounts = new Discounts();
    $result = $discounts->processCart(json_decode(file_get_contents('https://raw.githubusercontent.com/teamleadercrm/coding-test/master/example-orders/order'.$args['orderID'].'.json'),true));
	$response = $response->withJson($result, 200);
	$response = $response->withHeader('Content-Type', 'application/json');
    return $response;
});
