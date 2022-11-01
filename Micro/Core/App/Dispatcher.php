<?php

namespace API\Core\App;


use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Dispatcher implements RequestHandlerInterface
{
    protected array $middleware = [];
    private int $index = 0;

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }
    private function getMiddleware()
    {
        if (isset($this->middleware[$this->index])) {
            return $this->middleware[$this->index];
        }
        return null;
    }
    public function loadPipeline(array $pipeline) : void
    {
        $this->middleware = $pipeline;

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $middleware = $this->getMiddleware();
        $this->index++;
        if (is_null($middleware)) {
            return $response;
        }
        return $middleware->process($request, $this);
    }
}