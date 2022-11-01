<?php


namespace API\Core\App;


use API\Core\Router\MRoute;
use API\Core\Utils\Logger;
use API\Interfaces\ContainerInterface;
use GuzzleHttp\Psr7\Request;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;

class DependencyLoader
{
    private ContainerInterface $ioc;

    public function __construct(ContainerInterface $ioc)
    {
        $this->ioc = $ioc;
    }
    public function loadDependencies(MRoute $matched, Request $request): array
    {
        if(gettype($matched->getCallable()) === 'object'){
            $params = $this->getFunctionParams($matched->getCallable());
            $typeCastList = $this->parseParams($params);
            $callParams = $this->bootParameters($typeCastList,$matched->getParams());
            if($callParams === null) {
                $callParams = [];
            }
            $Callable = [
                $matched->getCallable(),
                $callParams
            ];
        } else {
            $params = $this->getClassMethodParams(
                $matched->getCallable()[0],
                $matched->getCallable()[1]
            );
            $typeCastList = $this->parseParams($params);
            $callParams = $this->bootParameters($typeCastList,$matched->getParams());
            if($callParams === null) {
                $callParams = [];
            }
            $Callable = [
                [$matched->getCallable()[0],$matched->getCallable()[1]],
                $callParams
            ];
        }
        return $Callable;
    }
    private function bootParameters($typeCastList,$callableParams)
    {
        $callbackParams=null;
        for($p=0;$p<count($typeCastList);$p++){
            $fnParameter =array_keys($typeCastList[$p])[0];
            $paramCAST = $typeCastList[$p][$fnParameter];
            if(isset($callableParams[$fnParameter])){
                $callbackParams[] =  $this->typeCast($callableParams[$fnParameter],$paramCAST);
            } else {
                if(in_array($paramCAST,['int','string'])){
                    $callbackParams[] = $this->typeCast('',$paramCAST);
                } else {
                    $callbackParams[] =  $this->ioc->get($paramCAST);
                }
            }
        }
        return $callbackParams;

    }
    private function getClassMethodParams($controller,$method): array
    {
        $class=null;
        try {
            $class = new ReflectionClass($controller);
        } catch (ReflectionException $e) {
        }
        try {
            $method = $class->getMethod($method);
        } catch (ReflectionException $e) {
        }
        return $method->getParameters();
    }
    private function getFunctionParams($fn): array
    {
        $refFunction=null;
        try {
            $refFunction = new ReflectionFunction($fn);
        } catch (\ReflectionException $e) {
        }
        return $refFunction->getParameters();

    }
    private function parseParams($parameters): array
    {
        $params=[];
        for($i=0;$i<count($parameters);$i++){
            $params[] = [
                $parameters[$i]->name => $parameters[$i]->getType()->getName()
            ];
        }
        return $params;
    }
    private function typeCast(mixed $value, string $cast): int|string
    {
        return match ($cast) {
            'int' => (int)$value,
            'string' => (string)$value,
            default => $value,
        };
    }

}