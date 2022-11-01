<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 18/02/2019
 * Time: 20:22
 */

namespace API\Validation;

use API\Core\Session\Session;
use API\Core\Utils\Validation;

class CaptchaCheck extends Validation
{
    public function __invoke($captcha): ?string
    {

        $human = unserialize(Session::get('captcha'));
        if($captcha !== $human->text){
            return $this->lang->translate('CAPTCHA_NO_MATCH');
        }
        return null;
    }
}
