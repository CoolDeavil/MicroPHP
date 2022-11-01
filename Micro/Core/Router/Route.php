<?php


namespace API\Core\Router;

use API\Core\Container\MicroDI;
use API\Core\Render\BladeRender;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;

class Route
{
    protected static ?MRouter $router;
    public static function boot(RouterInterface $router)
    {
        self::$router =$router;
    }
    public static function get(string $url, array|string|callable $callable, string $name): MRoute
    {
        return self::$router->get($url,$callable,$name);
    }
    public static function post(string $url, array|string|callable $callable, string $name): MRoute
    {
        return  self::$router->post($url,$callable,$name);
    }
    public static function patch(string $url, array|string|callable $callable, string $name): MRoute
    {
        return  self::$router->patch($url,$callable,$name);
    }
    public static function delete(string $url, array|string|callable $callable, string $name): MRoute
    {
        return  self::$router->delete($url,$callable,$name);
    }
    public static function resource(string $serviceName): RBuilder
    {
        return  self::$router->resource($serviceName);
    }
    public static function view(string $url, string $view, array $params = []): MRoute|bool
    {
        return  self::$router->view($url,$view);
    }
}


