<?php

namespace API\Core\App;

use API\Core\Router\Route;
use API\Core\Session\Session;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Middleware\RequestMiddleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use IntlDateFormatter;
use Psr\Http\Message\ServerRequestInterface;

class Micro
{
    protected ContainerInterface $ioc;
    protected Dispatcher $dispatch;
    protected RouterInterface $router;
    protected RenderInterface $render;

    public function __construct(
        ContainerInterface $ioc,
        Dispatcher $dispatch,
        RouterInterface $router,
        RenderInterface $render
    )
    {
        $this->ioc = $ioc;
        $this->dispatch = $dispatch;
        $this->router = $router;
        $this->render = $render;
        Session::getInstance();
        $this->router->get('/registered-routes',[ $this, 'routeMapping'],'Micro.routes');
        $this->router->post('/api/switch-language',[ $this, 'switch'],'Micro.switchLanguage');
    }
    public function run(ServerRequestInterface $request): Response
    {
        $this->checkAllowedCORS();
        // Load Routes declared in each module constructor
        $this->lodModulePipe();
        // Load Routes files
        $ioc = $this->ioc;
        Route::boot($this->router);
        include_once APP_ROUTES_FILE;
        // Check for URL match.
        $match = $this->router->dispatch($request);
        if($match){
            $this->loadAppSettings();
            $pipe= $this->loadMiddleware($this->router->getMatchedRoute()->getMiddleware());
            $this->dispatch->loadPipeline($pipe);
            return $this->dispatch->run($request);
        }else{
           $response = new Response();
           $response->getBody()->write($this->render->render('err404',['uri' => $request->getUri()->getPath()]));
           return $response;
        }
    }
    public function switch(Request $request) : Response
    {
        $locales = [
            'pt' => 'pt_PT',
            'en' => 'en_GB',
            'fr' => 'fr_FR'
        ];
        Session::set('ACTIVE_LANG', $request->getParsedBody()['language']);
        Session::set('LOCALE', $locales[$request->getParsedBody()['language']]);
        return (new Response())
            ->withStatus(200)
            ->withHeader('Location', Session::get('LAST_INTENT'));
    }
    public function loadMiddleware(array $routeMiddleware = []): array
    {
        $middlewarePipe =  include_once MIDDLEWARE_PIPE;
        $pipeline = array_unique(array_merge(
            $middlewarePipe,
            $routeMiddleware
        ),SORT_REGULAR);
        $pipeline[] = RequestMiddleware::class;
        $compiled = [];
        foreach ($pipeline as $middleware){
            $compiled[] = $this->ioc->get($middleware);
        }
        unset($middlewarePipe);
        unset($pipeline);
        return ($compiled);
    }
    public function lodModulePipe() : void
    {
        $params = [
          'router' => $this->router,
          'render' => $this->render,
        ];
        $modulePipe = include_once MODULES_PIPE;
        foreach ($modulePipe as $module) {
            $this->ioc->get($module, $params);
        }
    }

    public function routeMapping(Request $request) : Response
    {
        $view = (string)$this->render->render('adminPanel',[
            'tableBody' => $this->registeredRoutes($this->router::getAllRoutes())
        ]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;
    }
    private function registeredRoutes(array $registered) : string {
        $html = '';
        foreach (array_keys($registered) as $method){
            $html .= '<tr><td colspan="5" class="method">'.$method.'</td></tr>';
            foreach ($registered[$method] as $route ) {
                $params="";
                if(isset($route["parameters"][0])){
                    foreach ($route["parameters"] as $rule){
                        $params .= "<div>".$rule['key']." => ".$rule['value']."</div>";
                    }
                }
                $html .= '<tr>
                <td>'.$route["route"].'</td>
                <td>'.$params.'</td>
                <td>'.$route["controller"].'</td>
                <td>'.$route["action"].'</td>
                <td>'.$route["name"].'</td>
                </tr>';
            }
        }
        return $html;
    }
    private function loadAppSettings()
    {
        if (Session::get('ACTIVE_LANG')) {
            $this->render->addGlobal('app_lang', Session::get('ACTIVE_LANG'));
        } else {
            $this->render->addGlobal('app_lang', APP_LANG);
        }
        $this->render->addGlobal('cur_time',$this->footDate(Session::get('ACTIVE_LANG')));
    }
    private function footDate(string $locale): string
    {
        return datefmt_create(
            $locale,
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            date_default_timezone_get(),
            IntlDateFormatter::GREGORIAN
        )->format(time());
    }
    private function checkAllowedCORS(){
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $allowed = [
                'http://localhost:8000'
            ];
            array_unshift($allowed,"http://$_SERVER[HTTP_HOST]");
            if( !in_array($_SERVER['HTTP_ORIGIN'], $allowed) ) {
                exit(0);
            }
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        // Access-Control headers are received during OPTIONS requests
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                // may also be using PUT, PATCH, HEAD etc
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

            exit(0);
        }
    }
    public function getAllRoutesCLI() : array
    {
        $this->lodModulePipe();
        $this->router->buildResourceRoutes();
        return $this->router::getAllRoutes();
    }

}