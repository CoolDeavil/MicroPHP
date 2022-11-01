<?php


use API\Core\Utils\Logger;

function testJob($data) {


    Logger::log('DUMMY JOB STARTED EXECUTION ');
    Logger::log('Time Start '.time() );
    sleep(5);
    Logger::log('Time End  '.time() );

}
