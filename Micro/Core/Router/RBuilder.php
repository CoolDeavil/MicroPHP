<?php


namespace  API\Core\Router;


use API\Interfaces\RouterInterface;


class RBuilder
{
    public mixed $controller;
    public string $serviceRoutePrefix;
    public string $routeRegex;
    public string $serviceName;
    public array $middleware;
    public array $serviceRoutes = [];
    private string $identifier;

    public function __construct(string $serviceName, $serviceRoutes)
    {
        $this->serviceRoutes = $serviceRoutes;
        $this->serviceName = $serviceName;
        $this->identifier = 'id';
        $this->middleware = [];
        $this->routeRegex = MIXED;
    }
    function controller(mixed $controller): static
    {
        $this->controller = $controller;
        return $this;
    }
    function setServiceRoutes(array $routes): static
    {
        $this->serviceRoutes = $routes;
        return $this;
    }
    function urlPrefix(string $prefix): static
    {
        $this->serviceRoutePrefix = $prefix;
        return $this;
    }
    function urlIdentifier(string $varName, string $regex = MIXED): static
    {
        $this->routeRegex = $regex;
        $this->identifier = $varName;
        return $this;
    }
    function middleware(array $middleware): static
    {
        $this->middleware = $middleware;
        return $this;
    }

    public function report(): array
    {
        $routes=[];
        foreach (['GET','POST','PATCH','DELETE'] as $method ){
            $route = [];
            foreach ($this->serviceRoutes[$method] as $id=>$action){
                $urlPath = $this->serviceRoutePrefix.$id;
                if (strpos($urlPath, '/:id')) {
                    if('/:'.$this->identifier != '/:id'){
                        $urlPath = str_replace('/:id','/:'.$this->identifier , $urlPath);
                    }
                }
                $route['route'] = $urlPath;
                $route['method'] = $method;
                $route['name'] = $this->serviceName.'.'.$action;
                $route['controller'] = [$this->controller,$action];
                $route['routeRegex'] = $this->routeRegex;
                $route['identifier'] = $this->identifier;
                $route['middleware'] = $this->middleware;
                $routes[] = $route;
            }
        }
        return $routes;
    }
    function generate(RouterInterface $router,array $routes) : void {
       foreach ($routes as $route ){

           /**@var MRoute $rt */
           $rt =$router->add(
               $route['method'] ,
               $route['route'] ,
               $route['controller'],
               $route['name'],
           );
           if (strpos($route['route'], ':')) {
               $rt->with($this->identifier,$this->routeRegex);
           }
           $rt->middleware($this->middleware);
       }
    }
}