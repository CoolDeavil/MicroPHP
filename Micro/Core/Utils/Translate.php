<?php


namespace API\Core\Utils;


use API\Core\Session\Session;

class Translate
{

    public static function translate(string $key): string
    {
        $activeLang = Session::get('ACTIVE_LANG');
        $translateKeys = include LANGUAGE_FOLDER.$activeLang.'/trans_'.$activeLang.'.php';;
        if(isset($translateKeys[$key])){
            return $translateKeys[$key];
        }
        return $key;

    }
}