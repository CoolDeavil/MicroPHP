<?php

require_once('..'.
    DIRECTORY_SEPARATOR.'vendor'.
    DIRECTORY_SEPARATOR.'autoload.php');



const BUILD_RELEASE = true;
const DEV_SERVER_PORT = 3000;
//define("DEV_SERVER_URL", "http://$_SERVER[HTTP_HOST]" . ':'.DEV_SERVER_PORT.'/');
//const DEV_SERVER_URL = "http://localhost" . ':' . DEV_SERVER_PORT . '/';
const DEV_SERVER_URL = "http://192.168.1.65" . ':' . DEV_SERVER_PORT . '/';


const APP_LANG = 'en';
const APP_TITLE = 'MicroPHP';
const CAPTCHA_LENGTH = 8;

const PSR4_FOLDER = 'Micro';

const MIDDLEWARE = 'middleware.pipe.php';
const ROUTE_FILE = 'routes.php';
//const ROUTE_FILE = 'routes_dev.php';
const ALLOWED_CORS = 'cors.config.php';
const MODULES = 'modules.pipe.php';
const CAPTCHA_FONT = './fonts/AnonymousClippings.ttf';


define('APP_ASSET_BASE', "http://$_SERVER[HTTP_HOST]/");
define('MODULES_PIPE', realpath('..' . DIRECTORY_SEPARATOR . PSR4_FOLDER) . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR. MODULES);
define('MIDDLEWARE_PIPE', realpath('..' . DIRECTORY_SEPARATOR . PSR4_FOLDER) . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR. MIDDLEWARE);
define('APP_ROOT', realpath('..'.DIRECTORY_SEPARATOR.PSR4_FOLDER).DIRECTORY_SEPARATOR);
define('APP_ROUTES_FILE', realpath('..' . DIRECTORY_SEPARATOR ) . DIRECTORY_SEPARATOR .PSR4_FOLDER. DIRECTORY_SEPARATOR . ROUTE_FILE );
define('LANGUAGE_FOLDER', realpath('..'.DIRECTORY_SEPARATOR.'assets/lang/').DIRECTORY_SEPARATOR);
define('ALLOWED_CORS_FILE', realpath('..' . DIRECTORY_SEPARATOR . PSR4_FOLDER) . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR. ALLOWED_CORS);

const APP_VIEWS = APP_ROOT . "Views" . DIRECTORY_SEPARATOR;


########################
# App Crypt
########################
const APP_SECRET_KEY = 'Some-Secret-String';
const APP_KEY = 'A1f05ac35a38eZee5167be11a27aae9d';
const APP_KEY_ALGORITHM = 'md5';

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'routes.regex.php');

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'micro.database.php');

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'navbar.conf.php');

$bootstrap = require_once(realpath('../').
    DIRECTORY_SEPARATOR . PSR4_FOLDER.
    DIRECTORY_SEPARATOR . 'Config'.
    DIRECTORY_SEPARATOR . 'bootstrap.php' );
