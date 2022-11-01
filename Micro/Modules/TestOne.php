<?php


namespace API\Modules;


use API\Core\Utils\AppCrypt;
use API\Core\Utils\Translate;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Repository\AuthorRepository;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class TestOne
{
    protected RouterInterface $router;
    protected RenderInterface $render;

    public function __construct(RouterInterface $router, RenderInterface $render)
    {
        $this->router = $router;
        $this->render = $render;

        $this->router->get('/module-one',[$this,'index'],'TestOne.index');
    }

    public function index(Request $request, AppCrypt $cr) : Response
    {
        $view = $this->render->render('module1', ['crypt' => $cr->crypt('fooBar')]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;

    }
}