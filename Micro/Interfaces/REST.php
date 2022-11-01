<?php


namespace API\Interfaces;


use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface REST
{
    public function get(int $id = null ) : Response;
    public function post(Request $request) : Response;
    public function patch(Request $request,int $id) : Response;
    public function delete(Request $request,int $id) : Response;
}