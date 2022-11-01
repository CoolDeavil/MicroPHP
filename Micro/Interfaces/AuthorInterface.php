<?php


namespace API\Interfaces;


use API\Models\AppUser;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

interface AuthorInterface
{
    public function show(Request $request, Response $response);
    public function store(Request $request, Response $response);
    public function update(AppUser $user): void;
    public function destroy();
    public function validatePassCallBack();
    public function resetCaptcha();
    public function authorizeUser();
    public function resetPassword();
    public function confirmNewPass();
    public function validateUniqueEmail();
    public function validateUniqueUserName();
    public function validateEmailRegistered();
    public function validateCaptcha();

}


