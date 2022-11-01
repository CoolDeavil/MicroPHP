<?php


namespace API\Modules;


use API\Core\Container\MicroDI;
use API\Interfaces\REST;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class ModuleAPI implements REST
{
    protected RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        $this->router->get('/api/api-controller',[$this,'index'],'ModuleAPI.index');
        $rest = MicroDI::getInstance([])->get('REST');

        $this->router->resource('restService')
            ->setServiceRoutes($rest)
            ->controller($this)
            ->urlPrefix('/api/rest')
            ->urlIdentifier('id',INTEGER);
    }
    public function index() : Response
    {
        $response = new Response();
        $response->getBody()->write(json_encode([
            'REST API' => true,
        ],JSON_PRETTY_PRINT));
        return $response;
    }
    public function get(int $id = null) : Response
    {
        $response = new Response;
        if($id){
            $response->getBody()->write(json_encode(['GET' => $id], JSON_PRETTY_PRINT));
        }else{
            $response->getBody()->write(json_encode(['GET' => 'All'], JSON_PRETTY_PRINT));
        }
        return $response;
    }
    public function post(Request $request): Response
    {
        $response = new Response;
        $response->getBody()->write(json_encode(['POST' => true], JSON_PRETTY_PRINT));
        return $response;
    }
    public function patch(Request $request, int $id) : Response
    {
        $response = new Response;
        $response->getBody()->write(json_encode(['PATCH' => $id], JSON_PRETTY_PRINT));
        return $response;
    }
    public function delete(Request $request, int $id) : Response
    {
        $response = new Response;
        $response->getBody()->write(json_encode(['DELETE' => $id], JSON_PRETTY_PRINT));
        return $response;
    }

}