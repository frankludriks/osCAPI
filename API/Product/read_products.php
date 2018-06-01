<?php
// include our OAuth2 Server object
require_once dirname(__DIR__).'/server.php';

// Handle a request to a resource and authenticate the access token
/*if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}

//scope allowed?
$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();
$scopeRequired = 'products'; // this resource requires "products" scope
if (!$server->verifyResourceRequest($request, $response, $scopeRequired)) {
  // if the scope required is different from what the token allows, this will send a "401 insufficient_scope" error
  $response->send();
  die;
}*/

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
use osCAPI\Objects\Product as Product;
 
// instantiate database and product object
/*$database = new Database();
$db = $database->getConnection();*/
 
// initialize object
$product = new Product();
 
// query products
$stmt = $product->read();
 
echo json_encode($stmt);

?>