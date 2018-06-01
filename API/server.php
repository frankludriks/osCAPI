<?php

include (dirname(__DIR__) . '/includes/configure.php');
$dsn      = 'mysql:dbname=' . DB_DATABASE . ';host=' . DB_SERVER .'';
$username = DB_SERVER_USERNAME;
$password = DB_SERVER_PASSWORD;

// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading OAuth2
require_once('OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

// Autoloading osCAPI
require_once('osCAPI/Autoloader.php');
osCAPI\Autoloader::register();

use osCAPI\Config\Medoo as Medoo;
 
// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage);

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

// Add the "Authorization Code" grant type (this is where the oauth magic happens)
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));