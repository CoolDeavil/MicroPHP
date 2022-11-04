<?php

namespace API\Middleware;

use API\Core\App\DependencyLoader;
use API\Core\Router\MRoute;
use API\Core\Session\Session;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RequestMiddleware implements MiddlewareInterface
{
    protected RouterInterface $router;
    protected RenderInterface $render;
    private DependencyLoader $depManager;

    public function __construct(RouterInterface $router, RenderInterface $render ,DependencyLoader $depManager )
    {
        $this->router = $router;
        $this->depManager = $depManager;
        $this->render = $render;
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        /**@var MRoute $matched */
        $matched = $this->router->getMatchedRoute();
        if (!is_bool($matched)) {
            if($matched->getUrlMethod() === 'VIEW'){
                $response = new Response();
                $view = $this->render->render($matched->getName());
                $response->getBody()->write($view);
                return $response;
            }
            $callBack =  $this->depManager->loadDependencies(
                $matched,
            );
            return call_user_func_array($callBack[0],$callBack[1]);
        }

        $response = new Response();
        $response->getBody()->write('404');
        return $response;
    }

}