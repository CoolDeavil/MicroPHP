<?php


namespace API\Middleware;


use API\Core\Session\Session;
use API\Core\Utils\Logger;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AdminMiddleware implements MiddlewareInterface
{

    public function __construct()
    {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {

        if (Session::get('REDIRECTED')) {
            Session::unsetKey('REDIRECTED');
            return $handler->handle($request);
        }
        if(Session::get('loggedUser')){
            if($this->checkLoggedUserPermissions($request)){
                return $handler->handle($request);
            } else {
                Session::set('FLASH_MESSAGE' , serialize([
                    'type' => 'isError',
                    'title' => 'Not Allowed',
                    'message' => 'Credentials dont Match...'
                ]));
                $response = new Response();
                return $response->withHeader('Location', '/' )
                    ->withStatus(301);
            }

        } else {
            if (in_array($request->getUri()->getPath(),
                [
                    '/api/author',
                    '/api/author/create',
                    '/api/author/authorize-user',
                    '/api/author/reset-captcha'
                ])) {
                return $handler->handle($request);
            }



            Session::set('FLASH_MESSAGE' , serialize([
                'type' => 'isError',
                'title' => 'Not Allowed',
                'message' => 'You need to LogIn to access the requested page'
            ]));
            $response = new Response();
            return $response->withHeader('Location', '/')
                ->withStatus(301);

        }

    }


    private function checkLoggedUserPermissions(Request $request) : bool {

        $urlPrefix = '/api/author/';
        $regex ="#^".$urlPrefix."#";
        if (preg_match_all($regex, $request->getUri()->getPath(), $match)) {
            if (preg_match_all('([0-9]+)', $request->getUri()->getPath(), $matches)) {
                if(Session::loggedUserID() !== (int)$matches[0][0]){
                    return false;

                } else {
                    return true;
                }
            }
        }
        Logger::log('Authorized' . Session::loggedUserID());
        return true;
    }
}