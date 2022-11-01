<?php
error_reporting(0);
use API\Core\App\Micro;
use API\Core\Container\MicroDI;
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');

$bootstrap = include(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'bootstrap.php');

$ioc = MicroDI::getInstance($bootstrap);
include_once APP_ROUTES_FILE;
/**@var Micro $app */
$app = $ioc->get(Micro::class);
$routeList = $app->getAllRoutesCLI();

$dl_h = html_entity_decode('─', ENT_NOQUOTES, 'UTF-8');  // horizontal wall
$mask = "|%6.6s | %-40.40s | %-20.20s | %-20.20s | %-20.20s |\n";

echo str_repeat($dl_h, 121);
printf("\033[32m\n");
printf($mask, 'Method', 'Route','Controller','Action','Name');
printf(str_repeat($dl_h, 121));
printf("\033[37m\n");

$methods = ['GET','POST','PATCH','DELETE','VIEW'];
$routeMethods=[];
$tCount=0;

foreach ($methods as $method) {
    if(isset($routeList[$method])){
        $routeMethods[$method] = count($routeList[$method]);
        $tCount += count($routeList[$method]);
    }
}

foreach ($methods as $method){
    if(isset($routeList[$method])){
        foreach ($routeList[$method] as $route ) {

            printf($mask, $method, $route["route"],$route["controller"],$route["action"],$route["name"]);
        }
    }
}

$sep = str_repeat($dl_h, 121);
printf("\033[32m$sep\n");
printf("\033[32mRoutes Available \033[33m $tCount \n");
printf("\033[37m");
$mask = "| %-11.11s | %-10.10s | %-10.10s | %-10.10s |\n";
printf($mask, 'GET','POST','PATCH','DELETE');
printf($mask,
    $routeMethods['GET'] ?? 0,
    $routeMethods['POST'] ?? 0,
    $routeMethods['PATCH'] ?? 0,
    $routeMethods['DELETE'] ?? 0
);
printf("\033[37m\n");
