<?php


namespace API\Core\Router;

class MRoute
{
    private string|null $name;
    private string $route;
    private mixed $callable;
    private string $urlMethod;
    private array $middleware =[];
    private bool $matched = false;

    private array $params = [];
    private array $vars = [];
    public function __construct($route, $callable, $method, $name)
    {
        $this->route = $route;
        $this->callable = $callable;
        $this->urlMethod = $method;
        $this->name = $name;
    }
    public function isMatched(): bool
    {
        return $this->matched;
    }
    public function setMatched(bool $matched) : self
    {
        $this->matched = $matched;
        return $this;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getRoute(): string
    {
        return $this->route;
    }
    public function getCallable()
    {
        return $this->callable;
    }
    public function getUrlMethod(): string
    {
        return $this->urlMethod;
    }
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
    public function getParams(): array
    {
        return $this->params;
    }
    public function with($key, $regex) : self {
        $this->params[$key]=$regex;
        return $this;
    }
    public function middleware(array $pipes ): array
    {
        return $this->middleware = $pipes;
    }
    public function match( string $url ) : bool {
        $path = preg_replace_callback( CAPTURE, [$this, 'validate'], $this->route );
        $regex = "#^$path$#";
        try{
            preg_match($regex, $url, $match);
        } catch (\Exception $e) {
            return false;
        } finally {
            if(count($match) === 0){
                return false;
            }
        }
        array_shift($match);
        if(isset($match[0])){
            for($i=0;$i<count($this->vars);$i++){
                $this->params[$this->vars[$i]]= $match[$i];
            }
        }
        unset($this->vars);
        return true;
    }
    private function validate($match){
        $this->vars[] = $match[1];
        if(isset($this->params[$match[1]])){
            return $this->params[$match[1]];
        }
        return MIXED;
    }
    public function getCallableUI() : string
    {
        if(gettype($this->callable) === 'object'){
            return $this->getUrlMethod() == 'VIEW'?'Route::View()':'Closure';
        }elseif (gettype($this->callable) === 'array'){
            switch (gettype($this->callable[0])){
                case 'object':
                    $parts = explode('\\', get_class($this->callable[0]));
                    break;
                case 'string':
                    $parts = explode('\\', $this->callable[0]);
                    break;
                default:
                    dump($this->callable);
                    dump("Strange Alien on Board");
                    die;
            }
            return $parts[count($parts)-1];
        }elseif (gettype($this->callable) === 'string'){
            if (!str_contains($this->callable, '@')) {
                die("Route Syntax Error -> Missing @ separator!....");
            }
            list($controller, $method ) = explode("@", $this->callable);
            $parts = explode('\\', $controller);
            return $parts[count($parts)-1];
        }
        return false;
    }
    public function getActionUI() : string
    {
        if(gettype($this->callable) === 'object'){
//            return 'routes.php';
            return $this->getUrlMethod() == 'VIEW'?'Callable':'routes.php';
        }elseif (gettype($this->callable) === 'array'){
            return $this->callable[1];
        }elseif (gettype($this->callable) === 'string'){
            if (!str_contains($this->callable, '@')) {
                die("Route Syntax Error -> Missing @ separator!....");
            }
            list($controller, $method ) = explode("@", $this->callable);
            return $method;
        }
        return false;
    }

}
