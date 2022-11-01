<?php


namespace API\Interfaces;
use API\Core\Router\MRoute;
use API\Core\Router\RBuilder;
use GuzzleHttp\Psr7\Request;

interface RouterInterface
{
    /**
     * @param $path
     * @param $callable
     * @param null $name
     * @return MRoute
     */
    public function get($path, $callable, $name = null): MRoute;
    public function post($path, $callable, $name = null);
    public function patch($path, $callable, $name = null);
    public function delete($path, $callable, $name = null);
    public function resource(string $serviceName): RBuilder;
    public function dispatch(Request $request);
    public function generateURI($slug, array $params = null);
    public function getMatchedRoute();
    public function setMethod(Request $request);
    public static function getAllRoutes() : array;
}
