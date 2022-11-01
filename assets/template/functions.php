<?php


use GuzzleHttp\Psr7\Request;

require_once(realpath('../') .
    DIRECTORY_SEPARATOR . 'Micro' .
    DIRECTORY_SEPARATOR . 'Config' .
    DIRECTORY_SEPARATOR . 'includes.php');


$func = function (Request $request,string $name, string $city, int $id ): string
{
    return 'Hello ' . $name;
};
$func2 = function (int $age, int $year, string $job ): string
{
    return 'Hello ';
};


$params =  getFuncParams($func);
dump($params);


echo "<hr>";
$params =  getFuncParams($func2);
dump($params);

die('EOP');


function getFuncParams(callable $func): array
{

    $refFunction=null;
    try {
        $refFunction = new ReflectionFunction($func);
    } catch (ReflectionException $e) {
    }
    $parameters = $refFunction->getParameters();
    $params = [];

    for($i=0;$i<count($parameters);$i++){
        $params[] = [
            $parameters[$i]->name => $parameters[$i]->getType()->getName()
        ];
    }
    return $params;
}




