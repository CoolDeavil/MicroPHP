<?php

namespace API\Middleware;

use API\Core\Utils\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareFour implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        $request = $request->withAttribute('MiddlewareFour', 'OK');
        Logger::log('MiddlewareFour on the Pipe');
        return $handler->handle($request);


    }

}