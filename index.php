<?php
// use App;
require_once(__DIR__.'/vendor/autoload.php');


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
// all of our endpoints start with /person
// everything else results in a 404 Not Found


$apiBaseName = 'App\Api\\';
$version = $uri[2];
$requestControllerName = $uri[3];

$controllerName = $apiBaseName . $version .'\\Controllers\\'.$requestControllerName;
if ($uri[1] !== 'api' || !isset($uri[2]) || !class_exists($controllerName ,true) ) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$rt = new $controllerName();

$rt->run();

