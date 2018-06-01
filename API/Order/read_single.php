<?php
// include our OAuth2 Server object
require_once dirname(__DIR__).'/server.php';

// Handle a request to a resource and authenticate the access token
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}

//scope allowed?
$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
$scopeRequired = 'orders'; // this resource requires "products" scope
if (!$server->verifyResourceRequest($request, $response, $scopeRequired)) {
  // if the scope required is different from what the token allows, this will send a "401 insufficient_scope" error
  $response->send();
  die;
}

use osCAPI\Objects\Order as Order;
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
//order id
$order_id = (int)$_POST['order_id'];
 
// initialize object
$order = new Order();

// query orders
$stmt = $order->read_one($order_id);

//output
echo json_encode($stmt);
?>