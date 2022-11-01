<?php
namespace API\Modules;


use API\Core\App\Controller;
use API\Core\Utils\AppCrypt;
use API\Core\Utils\Logger;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Middleware\AdminMiddleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use stdClass;

class MicroModule extends Controller
{
    protected RouterInterface $router;
    protected RenderInterface $render;

    public function __construct(RouterInterface $router, RenderInterface $render)
    {
        parent::__construct($router, $render);
        $this->router = $router;
        $this->render = $render;

        $this->router->get('/foo-bar-zoo',[$this,'index'],'MicroModule.index');
        $this->router->get('/json',[$this,'json']);
        $this->router->get('/async-task',[$this,'initiateTask'],'MicroModule.initiateTask');

        $this->router->get('/only-logged-users',[$this,'loggedUsers'],'MicroModule.loggedUsers')
        ->middleware([AdminMiddleware::class]);
    }
    public function index() : Response
    {
        $response = new Response();
        $view = $this->render->render('landing');
        $response->getBody()->write($view);
        return $response;
    }
    public function loggedUsers(Request $request) : Response
    {
        $response = new Response();
        $view = $this->render->render('loggedUsers');
        $response->getBody()->write($view);

        return $response;
    }
    public function json() : Response
    {
        $response = new Response();
        $response->getBody()->write(file_get_contents('./data.json'));
        return $response;
    }
    public function initiateTask() : Response
    {
        $this->lunchTask();
        $response = new Response();
        $response->getBody()->write('Job Started');
        return $response;
    }
    public function lunchTask() : void
    {
        $payload= [
            'foo'=>123,
            'bar'=>234,
        ];
        $task = new stdClass();
        $task->callable = 'testJob';
        $task->payload = $payload;
        $json_task = base64_encode(json_encode($task));
        Logger::log('Initializing Async Task');
        $this->executeAsyncShellCommand("php -f slave.php".escapeshellcmd($json_task));

    }
}



