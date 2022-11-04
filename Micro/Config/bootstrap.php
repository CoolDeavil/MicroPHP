<?php

use API\Core\App\Dispatcher;
use API\Core\App\DependencyLoader;
use API\Core\App\Micro;
use API\Core\Database\Database;
use API\Core\Render\BladeRender;
use API\Core\Router\MRoute;
use API\Core\Router\MRouter;
use API\Core\Router\RBuilder;
use API\Core\Utils\AppCrypt;
use API\Core\Utils\NavBuilder\NavBuilder;
use API\Core\Utils\Translate;
use API\Core\Utils\Validator;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;
use API\Middleware\AdminMiddleware;
use API\Middleware\LastIntent;
use API\Middleware\MiddlewareFour;
use API\Middleware\MiddlewareOne;
use API\Middleware\MiddlewareTree;
use API\Middleware\MiddlewareTwo;
use API\Middleware\RequestMiddleware;
use API\Models\User;
use API\Modules\AdminModule;
use API\Modules\MicroModule;
use API\Modules\ModuleAPI;
use API\Modules\TestOne;
use API\Repository\AuthorRepository;
use API\Validation\CaptchaCheck;
use API\Validation\EqualTo;
use API\Validation\MinLength;
use API\Validation\NotEmpty;
use API\Validation\ValidEMail;
use GuzzleHttp\Psr7\ServerRequest;

return [

    'VERSION' => function(){
        return 'MicroPHP 0.0.0';
    },
    Micro::class => function(ContainerInterface $ioc) {
        $d = new Dispatcher();
        /**@var RouterInterface $router */
        $router = $ioc->get(RouterInterface::class);
        /**@var RenderInterface $render */
        $render = $ioc->get(RenderInterface::class);
        return new Micro(
            $ioc,
            $d,
            $router,
            $render,
            new NavBuilder($render,$router, new Translate())
           );
    },
    User::class => function(){
        return new User('','');
    },
    // Interfaces
    RouterInterface::class => function (ContainerInterface $ioc) {
        return MRouter::getInstance();
    },
    RenderInterface::class => function (ContainerInterface $ioc) {
        return new BladeRender($ioc);
    },
    // Framework Classes
//    MRoute::class => function($args){
//        extract($args);
//        /** @var $route */
//        /** @var $callable */
//        /** @var $method */
//        /** @var $name */
//        return new MRoute($route, $callable, $method, $name);
//    },
//    RBuilder::class => function() {
//        return new RBuilder();
//    },
    'ResourceRoutes' => function(){
        return [
            'GET' => [
                '' => 'index',
                '/:id' => 'show',
            ] ,
            'POST' => [
                '' => 'store',
            ],
            'PATCH' => [
                '/:id'=>'update',
            ],
            'DELETE' => [
                '/:id'=>'destroy'
            ],
        ];
    },
    'AuthorService' => function(){
        return [
            'GET' => [
                '' => 'index',
                '/:id/profile' => 'show',
                '/create' => 'create',
                '/end-session' => 'endSession',
                '/registered-users' => 'listRegisteredUsers',
                '/reset-captcha' => 'resetCaptcha',
            ],
            'POST' => [
                '' => 'store',
                '/reset-captcha' => 'resetCaptcha',
                '/authorize-user' => 'authorizeUser',
                '/reset-password' => 'resetPassword',
                '/validate-captcha' => 'validateCaptcha',
            ],
            'PATCH' => [
                '/:id'=>'update',
                '/update-profile/:id' => 'updateProfile',

            ],
            'DELETE' => [
                '/:id'=>'destroy'
            ]
        ];
    },
    'REST' => function(){
        return [
            'GET' => [
                '' => 'get',
                '/:id' => 'get',
            ] ,
            'POST' => [
                '' => 'post',
            ],
            'PATCH' => [
                '/:id'=>'patch',
            ],
            'DELETE' => [
                '/:id'=>'delete'
            ],
        ];
    },

    GuzzleHttp\Psr7\Request::class => function(){
        return ServerRequest::fromGlobals();
    },

    AuthorRepository::class => function(){
        return new AuthorRepository(Database::getInstance());
    },

    Translate::class => function () {
        return new Translate();
    },

    NavBuilder::class => function (ContainerInterface $ioc) {
        $router = $ioc->get(RouterInterface::class);
        $render = $ioc->get(RenderInterface::class);
        $translate = $ioc->get(Translate::class);
        return new NavBuilder($render,$router,  $translate);
    },
    AppCrypt::class => function( ContainerInterface $ioc) {
        return AppCrypt::getInstance();
    },

    // Modules
    MicroModule::class => function( $args,ContainerInterface $ioc) {
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new MicroModule($router,$render);
    },
    'MicroModule' => function( $args,ContainerInterface $ioc) {
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new MicroModule($router,$render);
    },
    ModuleAPI::class => function( $args,ContainerInterface $ioc) {
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new ModuleAPI($router);
    },
    AdminModule::class => function($args,ContainerInterface $ioc){
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        /** @var $repo AuthorRepository */

        $repo = $ioc->get(AuthorRepository::class);
        return new AdminModule(
            $router,
            $render,
            $repo,
            New Validator($ioc)
        );

    },
    TestOne::class => function($args, ContainerInterface $ioc) {
        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */
        return new TestOne($router,$render);
    },

    //Middleware
    LastIntent::class => function(ContainerInterface $ioc) {
        return new LastIntent([]);
    },
    RequestMiddleware::class => function($args,ContainerInterface $ioc) {
//        /**@var RouterInterface $router */
//        $router = $ioc->get(RouterInterface::class);
//        /** @var $render RenderInterface */
//        $render = $ioc->get(RenderInterface::class);

        extract($args);
        /** @var $router RouterInterface */
        /** @var $render RenderInterface */


        return new RequestMiddleware(
            $router,
            $render,
            new DependencyLoader($ioc));
    },
    AdminMiddleware::class => function(ContainerInterface $ioc){
        return new AdminMiddleware();
    },

    // Test Middleware
    MiddlewareOne::class => function(ContainerInterface $ioc){
        return new MiddlewareOne();
    },
    MiddlewareTwo::class => function(ContainerInterface $ioc){
        return new MiddlewareTwo();
    },
    MiddlewareTree::class => function(ContainerInterface $ioc){
        return new MiddlewareTree();
    },
    MiddlewareFour::class => function(ContainerInterface $ioc){
        return new MiddlewareFour();
    },

    // Validations
    'minLength' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new MinLength($translate);
    },
    'notEmpty' => function(ContainerInterface $container){
        $translate = $container->get(Translate::class);
        return new NotEmpty($translate);
    },
    'equalTo' => function (ContainerInterface $container) {
        return new EqualTo( $container->get(Translate::class));
    },
    'validEmail' => function (ContainerInterface $container) {
        return new ValidEMail( $container->get(Translate::class));
    },
    'captchaCheck' => function (ContainerInterface $container) {
        return new CaptchaCheck( $container->get(Translate::class));
    },

];
