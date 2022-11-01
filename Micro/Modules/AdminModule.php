<?php


namespace API\Modules;


use API\Core\App\Controller;
use API\Core\Container\MicroDI;
use API\Core\Session\Session;
use API\Core\Utils\AppCrypt;
use API\Core\Utils\CaptchaGen;
use API\Core\Utils\Logger;
use API\Core\Utils\Validator;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;

use API\Middleware\AdminMiddleware;
use API\Models\User;
use API\Repository\AuthorRepository;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class AdminModule extends Controller
{
    protected RouterInterface $router;
    protected RenderInterface $render;
    private AuthorRepository $repo;
    private Validator $check;

    public function __construct(
        RouterInterface $router,
        RenderInterface $render,
        AuthorRepository $repo,
        Validator $check
    )
    {
        parent::__construct($router, $render);
        $this->router = $router;
        $this->render = $render;
        $this->repo = $repo;
        $this->check = $check;

        $admin = MicroDI::getInstance([])->get('AuthorService');

        $this->router->resource('AuthorService')
            ->setServiceRoutes($admin)
            ->controller($this)
            ->urlPrefix('/api/author')
            ->urlIdentifier('user',INTEGER)
            ->middleware([AdminMiddleware::class]);

    }

    public function index(Request $request) : Response
    {
        $viewData = [
            'pass' => '',
            'email' => '',
            'rme' => false
        ];
        $data = $this->resolveRedirectData([
            'old_data',
        ]);
        if(isset($data['old_data'])){
            // Resolve keys
            $viewData['pass'] = $data['old_data']['pass'];
            $viewData['email'] = $data['old_data']['email'];
            $viewData['rme'] = $data['old_data']['rme'] ?? false;
        }
        if($this->isCookieSet($request,'rme')){
            $data = $this->getCookie($request,'rme');
            $viewData['email']= AppCrypt::getInstance()->decrypt($data[0]);
            $viewData['pass'] = AppCrypt::getInstance()->decrypt($data[1]);
            $viewData['rme'] = true;
        }


        $view = $this->render->render('userLogIn',['data' => $viewData]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;
    }
    public function show(Request $request, int $user) : Response
    {
        $userObj =  $this->repo->get($user);
        $view = $this->render->render('userProfile',['user' => $userObj]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;
    }
    public function resetCaptcha(Request $request) : Response
    {
        $captcha = CaptchaGen::generate();
        $response = new Response();
        $response->getBody()->write(json_encode(['image' => $captcha->image]));
        return $response;
    }
    public function create(Request $request) : Response
    {
        $viewData = [
            'name' => '',
            'email' => '',
            'pass' => '',
            'language' => APP_LANG,
            'captcha' => ''
        ];
        $data = $this->resolveRedirectData([
            'old_data',
            'errors'
        ]);
        if(isset($data['old_data'])){
            // Resolve keys
            $viewData['name'] = $data['old_data']['name'];
            $viewData['email'] = $data['old_data']['email'];
            $viewData['pass'] = $data['old_data']['pass'];
            $viewData['language'] = $data['old_data']['language'];
            $viewData['captcha'] = $data['old_data']['captcha'];
            $captcha = unserialize(Session::get('captcha'));
            $viewData['errors'] = $data['errors'];
        } else {
            $captcha = CaptchaGen::generate();
        }
        $view = $this->render->render('userForm', [
            'captcha' => $captcha->image,
            'data' => $viewData
        ]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;

    }
    public function store(Request $request) : Response
    {
        $this->check->init($request);
        $this->check->field('email')->rule('notEmpty');
        $this->check->field('email')->rule('validEmail');
        $this->check->field('name')->rule('notEmpty');
        $this->check->field('pass')->rule('notEmpty');
        $this->check->field('captcha')->rule('notEmpty');
        $this->check->field('captcha')->rule('captchaCheck');

        $validated = $this->check->validate();
        if ($validated) {
            $newUserID = new User($request->getParsedBody()['email'], AppCrypt::hashFactory($request->getParsedBody()['pass']));
            $newUserID->setName($request->getParsedBody()['name'])
                ->setCreated(time())
                ->setLogged(time())
                ->setEdited(time())
                ->setPass(AppCrypt::hashFactory($request->getParsedBody()['pass']))
                ->setAbout('')
                ->setAvatar('default_avatar.png')
                ->setLang($request->getParsedBody()['language']);

            $id = $this->repo->store($newUserID);

            $loggedUser = $this->repo->get($id);
            Session::set('FLASH_MESSAGE' , serialize([
                'type' => 'isSuccess',
                'title' => 'Registration Successful',
                'message' => 'Welcome <strong>'.$loggedUser->getName().'</strong>, Your Register was completed'
            ]));
            Session::authorize($loggedUser);
            return (new Response())
                ->withStatus(200)
                ->withHeader('Location', $this->router->generateURI('AuthorService.show', ['user'=>$loggedUser->getId()]));

        } else {
            $result=false;
            $payload = [
                'old_data' => $request->getParsedBody(),
                'errors' => $this->check->fetchErrors(),
                'flash' => [
                    'type' => 'isError',
                    'title' => 'Validation Error',
                    'message' => 'You data contains invalid values...',
                ]
            ];

            $location = $this->router->generateURI('AuthorService.create');
            return $this->handleResponse(
                $request,
                $result,
                $payload,
                $location
            );

        }

//        if($this->validateDataEntry($request)){
//
//            $newUserID = new User($request->getParsedBody()['email'], AppCrypt::hashFactory($request->getParsedBody()['pass']));
//            $newUserID->setName($request->getParsedBody()['name'])
//                ->setCreated(time())
//                ->setLogged(time())
//                ->setEdited(time())
//                ->setPass(AppCrypt::hashFactory($request->getParsedBody()['pass']))
//                ->setAbout('')
//                ->setAvatar('default_avatar.png')
//                ->setLang($request->getParsedBody()['language']);
//
//            $id = $this->repo->store($newUserID);
//
//            $loggedUser = $this->repo->get($id);
//            Session::set('FLASH_MESSAGE' , serialize([
//                'type' => 'isSuccess',
//                'title' => 'Registration Successful',
//                'message' => 'Welcome <strong>'.$loggedUser->getName().'</strong>, Your Register was completed'
//            ]));
//            Session::authorize($loggedUser);
//            return (new Response())
//                ->withStatus(200)
//                ->withHeader('Location', $this->router->generateURI('AuthorService.show', ['user'=>$loggedUser->getId()]));
//
//        } else {
//            $result=false;
//            $payload = [
//                'old_data' => $request->getParsedBody(),
//                'flash' => [
//                    'type' => 'isError',
//                    'title' => 'Validation Error',
//                    'message' => 'You data contains invalid values...',
//                ]
//            ];
//
//            $location = $this->router->generateURI('AuthorService.create');
//            return $this->handleResponse(
//                $request,
//                $result,
//                $payload,
//                $location
//            );
//
//        }
    }
    private function validateDataEntry(Request $request) : bool
    {
        $human = unserialize(Session::get('captcha'));
        if($request->getParsedBody()['captcha'] !== $human->text){
            return false;
        }
        if($request->getParsedBody()['name'] == '' ){
            return false;
        }
        if($request->getParsedBody()['email'] == '' ){
            return false;
        }
        if($request->getParsedBody()['pass'] == '' ){
            return false;
        }
        return true;
    }
    public function updateProfile(Request $request, int $user) : Response
    {
        $repoUser = $this->repo->get($user);
        $repoUser->setAbout($request->getParsedBody()['about'])
            ->setLang($request->getParsedBody()['language'])
            ->setEdited(time());

        $this->repo->update($repoUser);

        Session::set('FLASH_MESSAGE' , serialize([
            'type' => 'isSuccess',
            'title' => 'Profile Updated',
            'message' => 'Your Profile data was Updated'
        ]));
        Session::authorize($repoUser);

        Session::set('REDIRECTED',true);

        return (new Response())
            ->withStatus(200)
            ->withHeader('Location', $this->router->generateURI('AuthorService.show',['user'=>$user]));

    }
    public function listRegisteredUsers(Request $request) : Response
    {
        $users = [];
        /**@var User $user */
        foreach ($this->repo->collection() as $user ) {
            $entry = [];
            $entry['name'] = $user->getName();
            $entry['email'] = $user->getEmail();
            $entry['lang'] = $user->getLang();
            $entry['timeLine'] = $user->getTimeLine();
            $entry['lastEdited'] = $user->getLastEdited();
            $entry['lastLogged'] = $user->getLastLogged();

            $users[] = $entry;
        }
        $view = $this->render->render('userList',[
            'jsonUsers' => $users,
            ]);
        $response = new Response();
        $response->getBody()->write($view);
        return $response;
    }
    private function isCookieSet(Request $request, string $name) : bool
    {
        if (isset($request->getCookieParams()[$name])) {
            return true;
        }
        return false;
    }
    private function getCookie(Request $request, string $name) : array {
        $cookie = $request->getCookieParams()[$name];
        return explode("::", urldecode($cookie));
    }
    private function setCookie(string $name, string $data, int $expires = 86400 * 7 ){  //One Day
        setcookie($name, $data, time() + $expires, '/'); // 86400 = 1 day

    }
    public function authorizeUser(Request $request) : Response
    {
        $user = new User($request->getParsedBody()['email'],AppCrypt::hashFactory($request->getParsedBody()['pass']));
        $user_id = $this->repo->validate($user);
        if($user_id>0){
            $repoUser = $this->repo->get($user_id);
            Session::authorize($repoUser);
            Session::set('FLASH_MESSAGE' , serialize([
                'type' => 'isSuccess',
                'title' => 'Logged In',
                'message' => 'Your Login was Successful: Last Login was ' . $repoUser->getLastLogged()
            ]));
            $repoUser->setLogged(time());
            if(isset($request->getParsedBody()['rme'])){
                $userMailCrypt = AppCrypt::getInstance()->crypt($request->getParsedBody()['email']);
                $userPassCrypt = AppCrypt::getInstance()->crypt($request->getParsedBody()['pass']);
                $cookie = "{$userMailCrypt}::{$userPassCrypt}";
                $this->setCookie('rme',$cookie);
            }
            $this->repo->update($repoUser);
            return (new Response())
                ->withStatus(200)
                ->withHeader('Location', Session::get('LAST_INTENT'));

        }else{
            $result=false;
            $payload = [
                'old_data' => $request->getParsedBody(),
                'flash' => [
                    'type' => 'isError',
                    'title' => 'Validation Error',
                    'message' => 'You credentials contain invalid values',
                ]
            ];
            $location = $this->router->generateURI('AuthorService.index');
            return $this->handleResponse(
                $request,
                $result,
                $payload,
                $location
            );

        }
    }
    public function endSession() : Response
    {
        Logger::log('Start Closing Session....');
        Session::destroy();
        Logger::log('Session Closed');
        setcookie('micro', 'DELETED!', time() - 3600, '/');
        return (new Response())
            ->withStatus(200)
            ->withHeader('Location', APP_ASSET_BASE);

    }
}