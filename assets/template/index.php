<?php

use API\Core\App\Micro;
use API\Core\Container\MicroDI;
use API\Modules\MicroModule;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');



$request = ServerRequest::fromGlobals();
$response = new Response();

/**@var $bootstrap */
$ioc = MicroDI::getInstance($bootstrap);

/**@var Micro $app */
$app = $ioc->get(Micro::class);

$response = $app->run($request);

Http\Response\send($response);
