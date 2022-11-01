<?php
/**@var MicroDI $ioc */

use API\Core\Container\MicroDI;
use API\Core\Router\Route;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

Route::view('/','landing');

Route::view('/show-me-a-view','module2');

Route::get('/version',function() use($ioc) : Response {
    $ver = $ioc->get('VERSION');
    $response = new Response();
    $response->getBody()->write(json_encode(['Version' => $ver], JSON_PRETTY_PRINT) );
    return $response;
},'facadeTrials32')
->middleware([]);


Route::get('/fooBarAndZoo/:animal',function(Request $request, string $animal) : Response {
    $response = new Response();
    $url = $request->getUri()->getPath();
    $response->getBody()->write("FooBarAndZoo Page <h1>$animal = $url</h1>");
    return $response;
},'facadeTrials3762')
->with('animal',LETTERS);


