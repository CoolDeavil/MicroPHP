<?php


namespace API\Core\Utils;


use API\Core\Container\MicroDI;
use DateTime;
use Illuminate\Support\Facades\Log;

class DateDiff
{
    public static function diff($start, $end): string
    {

        $d_start = null;
        $d_end = null;

        if ($end == 0) {
            return 'NaN';
        }
        $start  = date('Y-m-d H:i:s', $start);
        $end    = date('Y-m-d H:i:s', $end);
        try {
            $d_start = new DateTime($start);
        } catch (\Exception $e) {
        }
        try {
            $d_end = new DateTime($end);
        } catch (\Exception $e) {
        }
        $diff = $d_start->diff($d_end);


        $year     = (int) $diff->format('%y');
        $month    = (int) $diff->format('%m');
        $day      = (int) $diff->format('%d');
        $hour     = (int) $diff->format('%h');
        $min      = (int) $diff->format('%i');
        $sec      = (int) $diff->format('%s');


        $bootstrap = require_once(realpath('../').
            DIRECTORY_SEPARATOR . PSR4_FOLDER.
            DIRECTORY_SEPARATOR . 'Config'.
            DIRECTORY_SEPARATOR . 'bootstrap.php' );

        $ioc = MicroDI::getInstance([]);

        /**@var $tra Translate */
        $tra = $ioc->get(Translate::class);

        $dateDif =[
            0 => [
                'label' => $year>1?$tra->translate('YEARS'):$tra->translate('YEAR'),
                'value' => $year,
            ],
            1 => [
                'label' => $month>1?$tra->translate('MONTHS'):$tra->translate('MONTH'),
                'value' => $month,
            ],
            2 => [
                'label' => $day>1?$tra->translate('DAYS'):$tra->translate('DAY'),
                'value' => $day,
            ],
            3 => [
                'label' => $hour>1?$tra->translate('HOURS'):$tra->translate('HOUR'),
                'value' => $hour,
            ],
            4 => [
                'label' => $min>1?$tra->translate('MINUTES'):$tra->translate('MINUTE'),
                'value' => $min,
            ],
            5 => [
                'label' => $sec>1?$tra->translate('SECONDS'):$tra->translate('SECOND'),
                'value' => $sec,
            ],
        ];
        $timeLapse = '';
        foreach ($dateDif as $key => $data) {
            if ($data['value'] != '0') {
                if ($key == 5) {
                    if ($timeLapse != '') {
                        $timeLapse .= 'e ' .$data['value'] .' '. $data['label'] . ' ';
                    } else {
                        $timeLapse .= $data['value'] .' '. $data['label'] . ' ';
                    }
                } else {
                    $timeLapse .= $data['value'] .' '. $data['label'] . ' ';
                }
            }
        }
        if($timeLapse !== ''){
            $timeLapse = $timeLapse . ' ' .$tra->translate('BEFORE');
        }
        return $timeLapse;
    }
}