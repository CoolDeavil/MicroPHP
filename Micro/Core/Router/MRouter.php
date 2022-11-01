<?php

namespace  API\Core\Router;

use API\Core\Render\BladeRender;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RouterInterface;
use API\Core\Utils\Logger;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;


class MRouter implements RouterInterface
{
    private static ?MRouter $instance = null;
    private static string $method = 'GET';
    public static array $routes = [];
    public static array $resourceRoutes = [];
    private ContainerInterface $ioc;

    private function __construct(ContainerInterface $ioc){
        $this->ioc = $ioc;
    }
    public static function getInstance($ioc): ?MRouter
    {
        if (!self::$instance) {
            self::$instance = new self($ioc);
        }
        return self::$instance;
    }
    public function resource(string $serviceName): RBuilder
    {
        return self::resourceRoute($serviceName);
    }
    public function resourceRoute(string $serviceName) : RBuilder
    {
        $resource = $this->ioc->get(RBuilder::class,['serviceName' => $serviceName]);
        self::$resourceRoutes[] = $resource;
        return $resource;

    }
    public function add($method, $path, $callable, $name = null): bool|MRoute
    {
        $route = new MRoute($path,$callable,$method,$name);
        self::$routes[$method][] = $route;
        return $route;
    }
    public function get($path, $callable, $name = null): MRoute
    {
        return self::add("GET", $path, $callable, $name);
    }
    public function post($path, $callable, $name = null): MRoute
    {
        return self::add("POST", $path, $callable, $name);
    }
    public function patch($path, $callable, $name = null): bool|MRoute
    {
        return self::add("PATCH", $path, $callable, $name);
    }
    public function delete($path, $callable, $name = null): bool|MRoute
    {
        return self::add("DELETE", $path, $callable, $name);
    }
    public function view(string $url, string $view): MRoute|bool
    {
        /**@var BladeRender $render $ */
        $render = $this->ioc->get(\API\Interfaces\RenderInterface::class);
        return self::add("VIEW", $url, function() use($view,$render) {
            $view_render = $render->render($view);
            $response = new Response();
            $response->getBody()->write($view_render);
            return $response;
    },'View'.'.'.$view);

    }

    public function dispatch(Request $request): bool
    {
        self::buildResourceRoutes();
        $request = $this->setMethod($request);
        self::$method = $request->getMethod();
        if(!isset(self::$routes[self::$method ] )){
            return false;
        }
        foreach (self::$routes[self::$method ] as $route) {
            /**@var $route MRoute */
            if ($route->match($request->getUri()->getPath())) {
                $route->setMatched(true);
                return true;
            }
        }
        if(isset(self::$routes['VIEW'] )){
            foreach (self::$routes['VIEW'] as $route) {
                /**@var $route MRoute */
                if ($route->match($request->getUri()->getPath())) {
                    $route->setMatched(true);
                    return true;
                }
            }
        }
        return false;
    }
    public function buildResourceRoutes() : void
    {
        /**@var RBuilder $routeBuilder */
        foreach (self::$resourceRoutes as $routeBuilder){
            $routes = $routeBuilder->report();
            $routeBuilder->generate($this,$routes);
        }
    }
    public function generateURI($slug, array $params = null) : string
    {
        /**@var $route MRoute */
        $methods = array_keys(self::$routes);
        foreach ($methods as $method ){
            if(isset(self::$routes[$method])){
                foreach (self::$routes[$method] as $route ){
                    if($route->getName() === $slug ) {
                        $path = $route->getRoute();
                        if($params){
                            foreach ($params as $k=>$v){
                                $path = str_replace($this->replaceRouteParams($k), $v, $path);
                            }
                        }
                        return $path;
                    }
                }
            }
        }
        return '';
    }
    public function replaceRouteParams($value): string
    {
        if(NEEDLE === NEEDLE_TWO_POINTS ){
            return NEEDLE_TWO_POINTS . $value;
        }else {
            return '{' . $value .'}';
        }
    }
    public function getMatchedRoute(): bool|MRoute
    {
        if(isset(self::$routes[ self::$method ])){
            foreach (self::$routes[ self::$method ] as $route) {
                /**@var $route MRoute */
                if ($route->isMatched()) {
                    return $route;
                };
            }
        }
        if(isset(self::$routes['VIEW'])){
            foreach (self::$routes['VIEW'] as $route) {
                /**@var $route MRoute */
                if ($route->isMatched()) {
                    return $route;
                };
            }
        }
        return false;
    }
    public function getExecutable(MRoute $matched) :  array|callable
    {
        $callable = $matched->getCallable();
        if(is_string($callable)) {
            if(!preg_match("#@#",$callable)){
                dump('BAD ROUTE NAME ');
                die(0);
            }
            $parts = explode('@', $callable);
            return [
                $this->ioc->get($parts[0]),
                $parts[1]
            ];
        } elseif (is_array($callable)){
            switch (gettype($callable[0])){
                case 'string':
                    return [
                        $this->ioc->get($callable[0]),
                        $callable[1]
                    ];
                case 'object':
                    return [
                        $callable[0],
                        $callable[1]
                    ];
            }
            die;
        }
        elseif (is_object($callable)){
            return $callable;
        }
        return [];
    }
    public static function getAllRoutes() : array
    {
        $methods = array_keys(self::$routes);
        $rtList = [];
        foreach ($methods as $method) {
            if(isset(self::$routes[$method])){
                $rtList[$method] = [];
                foreach (self::$routes[$method] as $route) {
                    $params = [];
                    $rp = $route->getParams();
                    foreach ( $rp as $key => $value) {
                        $params[] = [
                            'key' => $key,
                            'value' => $value,
                        ];
                    }
                    $rtList[$method][]= [
                        'method' => $method,
                        'route' => $route->getRoute(),
                        'controller' => $route->getCallableUI(),
                        'action' => $route->getActionUI(),
                        'name' => $route->getName(),
                        'parameters' => $params,
                    ];
                }
            }
        }
        return $rtList;
    }
    public function setMethod(Request $request) : Request
    {
        $parsedBody = $request->getParsedBody();
        if (array_key_exists('_method', $parsedBody)) {
            $request=$request->withMethod($parsedBody['_method']);
        }

        return $request;
    }


    public static function debug(): array
    {
        return self::$routes;
    }
    public static function debugResource(): array
    {
        return self::$resourceRoutes;
    }
}