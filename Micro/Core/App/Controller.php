<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 28/04/2019
 * Time: 23:11
 */

namespace API\Core\App;



use API\Core\Session\Session;
use API\Core\Utils\Logger;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use stdClass;

class Controller
{
    protected RenderInterface $render;
    protected RouterInterface $router;

    public function __construct(RouterInterface $router, RenderInterface $render)
    {
        $this->render = $render;
        $this->router = $router;
    }

    protected function handleResponse(
        Request $request,
        bool $status,
        array $payload,
        string $location
    ) : Response
    {
        $status?$http_code=200:$http_code=401;
        $method = $request->getHeader('X-Requested-With');
        if (isset($method[0]) && $method[0] == 'XMLHttpRequest') {
            $response = new Response();
            $response->getBody()->write(json_encode($payload['data']));
            return $response
                ->withStatus($http_code)
                ->withHeader('Content-Type', 'application/json');
        } else {
            if(isset($payload['flash'])){
                Session::set('FLASH_MESSAGE' , serialize([
                    'type' => $payload['flash']['type'],
                    'title' => $payload['flash']['title'],
                    'message' =>  $payload['flash']['message'],
                ]));
            }
            Session::set('REDIRECT_DATA', serialize($payload));
            return (new Response())
                ->withStatus($http_code)
                ->withHeader('Location', $location);
        }
    }

    protected function resolveRedirectData(array $keys) : array {
        $redirect =  Session::get('REDIRECT_DATA');
        $redirect =  unserialize($redirect);
        Session::unsetKey('REDIRECT_DATA');
        if($redirect){
            extract($redirect, EXTR_SKIP );
            $data = [];
            foreach ($keys as $key) {
                if(isset($redirect[$key])){
//                    $data[$key] = json_encode($redirect[$key]);
                    $data[$key] = $redirect[$key];
                } else{
                    $data[$key] = [];
                }
            }
        }else{
            $data = [];
        }
        return $data;
    }

    protected function executeAsyncShellCommand($command = null){
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            system($command." > NUL");
        }else{
            shell_exec("/usr/bin/nohup ".$command." >/dev/null 2>&1 &");
        }
    }


}
