<?php


namespace API\Core\Render;


use API\Core\Container\MicroDI;
use API\Core\Router\MRouter;
use API\Core\Session\Session;
use API\Core\Utils\NavBuilder\NavBuilder;
use API\Core\Utils\ScriptLoader;
use API\Interfaces\ContainerInterface;
use API\Interfaces\RenderInterface;
use IntlDateFormatter;
use Jenssegers\Blade\Blade;

class BladeRender implements RenderInterface
{
    protected Blade $blade;
    private string $templates = 'partials';
    protected array $globalHelpers = [];
    private ContainerInterface $ioc;

    public function __construct(ContainerInterface $ioc)
    {
        $this->blade = new Blade(APP_VIEWS, APP_VIEWS.DIRECTORY_SEPARATOR.'cached');
        $this->loadHelpers();
        $this->ioc = $ioc;
    }
    public function addPath(string $path): void
    {
        // TODO: Implement addPath() method.
    }
    public function addGlobal(string $key, $value): void
    {
        $this->globalHelpers[$key] = $value;

    }
    public function render(string $view, array $params = []): string
    {
        if (Session::get('ACTIVE_LANG')) {
            $this->addGlobal('app_lang', Session::get('ACTIVE_LANG'));
        } else {
            $this->addGlobal('app_lang', APP_LANG);
        }

        $viewData = array_unique(array_merge(
            $this->globalHelpers,
            $params
        ),SORT_REGULAR);
//        $viewData['footData']  = $this->footDate('pt');
        return $this->blade->make($view, $viewData)->render();
    }
    public function template($templateName, $params = []): string
    {
        $viewData = array_unique(array_merge(
            $this->globalHelpers,
            $params
        ),SORT_REGULAR);
        return $this->blade->make($this->templates.'.'.$templateName, $viewData)->render();
    }
    private function loadHelpers()
    {
        $this->globalHelpers = [
            'asset' => function(string $asset) : string{
                return APP_ASSET_BASE.$asset;
            },
            'url' => function(string $url, array $params=[]) : string{
                return rtrim(APP_ASSET_BASE,'/') . MRouter::getInstance($this->ioc)->generateURI($url,$params);
            },
            'urlDecode' => function(string $label) : string{
                return urldecode($label);
            },
            'flashMessage' => function() : string{
                if(Session::get('FLASH_MESSAGE')){
                    $flash = unserialize(Session::get('FLASH_MESSAGE'));
                    Session::unsetKey('FLASH_MESSAGE');
                    return $this->template('flash',['flash' => $flash]);
                }
                return '';
            },
            'navigation' => function() : string{
                /**@var NavBuilder $builder */
                $builder = MicroDI::getInstance([])->get(NavBuilder::class);
                return $builder->render();
            },
            'script' => function(string $label) : string{
                return ScriptLoader::getInstance()->script($label);
            },
            'style' => function(string $label) : string{
                return ScriptLoader::getInstance()->style($label);
            },

        ];

    }
}
