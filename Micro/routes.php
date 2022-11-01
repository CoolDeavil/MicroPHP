<?php
/**@var MicroDI $ioc */

use API\Core\Container\MicroDI;
use API\Core\Router\Route;
use API\Middleware\AdminMiddleware;
use GuzzleHttp\Psr7\Response;





//Route::view('/show-me-a-view','module2')->middleware([AdminMiddleware::class]);
Route::view('/show-me-a-view','module2');
//
//Route::get('/foo',function(Request $request) use($ioc) : Response {
//    $ver = $ioc->get('VERSION');
//    $response = new Response();
//    $response->getBody()->write('Foo Page '.$ver );
//    return $response;
//},'facadeTrials32')
//->middleware([]);
//
//
//Route::get('/fooBarAndZoo/:animal',function(Request $request, string $animal) : Response {
//    $response = new Response();
//    $response->getBody()->write("FooBarAndZoo Page <h4>$animal</h4>");
//    return $response;
//},'facadeTrials3762')
//->with('animal',LETTERS);
//
//Route::post('/fooBar',function(Request $request) : Response {
//    $response = new Response();
//    $response->getBody()->write('Foo Page');
//    return $response;
//},'facadeTrials25')
//->middleware([]);
//
//Route::patch('/fooBarZoo',function(Request $request) : Response {
//    $response = new Response();
//    $response->getBody()->write('FooBarZoo Page');
//    return $response;
//},'facadeTrials23')
//->middleware([]);
//
//Route::delete('/fooBarZoo',function(Request $request) : Response {
//    $response = new Response();
//    $response->getBody()->write('Foo Page');
//    return $response;
//},'facadeTrials23')
//->middleware([]);
//
//

