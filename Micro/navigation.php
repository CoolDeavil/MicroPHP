<?php

/**@var NavBuilder $nav  */


use API\Core\Session\Session;
use API\Core\Utils\NavBuilder\NavBuilder;

$user_avatar = 'images/default_avatar.png';
$userID  = Session::loggedUserID();
$user_avatar ='images/default_avatar.png';


$nav->link('MICRO1', 'MicroModule.index', 'fa-home');
$nav->link('MICRO2', 'Micro.routes', 'fa-road');
$nav->link('MICRO3', 'AuthorService.listRegisteredUsers', 'fa-key');
//$nav->link('MICRO1', 'TestOne.index', 'fa-star');


//$nav->drop('DROPDOWN')
//    ->entry('OPTION1', 'ModuleOne.index','fa-desktop')
//    ->entry('OPTION2', 'ModuleOne.index1','fa-desktop')
//    ->entry('OPTION3', 'ModuleOne.index2','fa-desktop', ['action'=>10])
//    ->separator()
//    ->entry('OPTION3', 'ModuleOne.index','fa-desktop');

$nav->admin()
   ->entry('REGISTER', 'AuthorService.create', 'fa-user-plus', [],'GUEST')
   ->entry('LOG_IN', 'AuthorService.index', 'fa-sign-in-alt', [],"GUEST")
   ->avatar($user_avatar, 'AuthorService.show', ['user'=>$userID],"USER")
   ->entry('LOG_OUT', 'AuthorService.endSession', 'fa-sign-out-alt', [],"USER");


