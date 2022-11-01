<?php

include  __DIR__.'/../Micro/Jobs/testJob.php';

\API\Core\Utils\Logger::log('Slave is working...');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$job_data = json_decode(base64_decode($argv[1]));
$callBack = $job_data->callable;
$data = $job_data->payload;
$callBack($data);

