<?php
/**
 * Created by PhpStorm.
 * User: Mike
 * Date: 18/02/2019
 * Time: 20:22
 */

namespace API\Validation;

use API\Core\Utils\Validation;

class EqualTo extends Validation
{
    public function __invoke($value, $compare): ?string
    {
        if ($value !== $compare) {
            return $this->lang->translate('NO_MATCH');
        }
        return null;
    }
}
