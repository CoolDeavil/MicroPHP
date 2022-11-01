<?php
/**@var MicroDI $ioc */
use API\Core\Container\MicroDI;
use API\Core\Router\Route;
use API\Middleware\AdminMiddleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

Route::get('/',function() use($ioc) : Response {
    $ver = $ioc->get('VERSION');
    $response = new Response();
    $response->getBody()->write(' <div class="title">MicroPHP <small>Website Root</small></div>');
    return $response;
},'facadeTrials32')
    ->middleware([]);

$rest = [
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


$auth = [
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

Route::resource('restService')
    ->setServiceRoutes($rest)
    ->controller(\API\Modules\ModuleAPI::class)
    ->urlPrefix('/api/rest')
    ->urlIdentifier('id',INTEGER);


Route::resource('userLogInService')
    ->setServiceRoutes($auth)
    ->controller(\API\Modules\AdminModule::class)
    ->urlPrefix('/api/author')
    ->urlIdentifier('user',INTEGER);


