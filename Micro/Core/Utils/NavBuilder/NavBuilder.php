<?php

namespace API\Core\Utils\NavBuilder;


use API\Core\Session\Session;
use API\Core\Utils\Translate;
use API\Interfaces\RenderInterface;
use API\Interfaces\RouterInterface;

class NavBuilder
{
    protected bool $translate;
    private string $currentURL;
    private Translate $tr;
    private array $navigation = [];

    private array $rendered = [];
    private array $renderedAuth = [];
    private RenderInterface $render;
    private RouterInterface $router;

    public function __construct(RenderInterface $render, RouterInterface $router,Translate $tr)
    {
        $this->render = $render;
        $this->translate = TRANSLATE;
        $this->tr = $tr;
        $this->currentURL = (string) parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->router = $router;
    }
    public function link($label, $url, $icon, $usage = null ): Link
    {
        return $this->navigation[] = new Link($label, $url, $icon, $usage);
    }
    public function drop($label): Drop
    {
        return $this->navigation[] = new Drop($label);
    }
    public function admin(): Auth
    {
        return $this->navigation[] = new Auth();
    }

    public function render() : string
    {

        $this->navigation = [];
        self::build();
        $templated = '';
        foreach ($this->rendered as $html) {
            $templated .= $html;
        }
        $authTemplated = '';
        foreach ($this->renderedAuth as $html) {
            $authTemplated .= $html;
        }

        $multi_language = MULTI_LANGUAGE?$this->render->template(ACTIVE_NAV_TEMPLATE.'.multiLanguage'):'';

        return $this->render->template(ACTIVE_NAV_TEMPLATE.'.navBarTemplate', [
            'navLinks' => $templated,
            'authLinks' => $authTemplated,
            'multi_language' => $multi_language
        ],
        );
    }

    private function build(): void
    {
        $nav = $this;
        include(APP_NAVBAR_FILE);
        $this->rendered = [];
        foreach ($this->navigation as $link) {

            $pieces = explode("\\", get_class($link));

            switch ($pieces[count($pieces)-1]) {
                case 'Link':
                    $this->rendered[] = self::buildLink($link);
                    break;
                case 'Drop':
                    $this->rendered[] = self::buildDrop($link);
                    break;
                case 'Auth':
                    $this->renderedAuth[] = self::buildAuth($link);
                    break;
                default:
                    die('wtf');
            }
        }
    }
    private function buildAuth(Auth $link): string
    {
        $usage = Session::get('loggedIn') ? 'USER' : 'GUEST';
        $authLinks = [];
        foreach ($link->getLinks() as $link_) {
            if ($link_->getUsage() === $usage) {
                $authLinks[] = $link_;
            }
        }

        $authLinksTemplate = '';
        for($i=0; $i<count($authLinks); $i++){
            $pieces = explode("\\", get_class($authLinks[$i]));
            switch ($pieces[count($pieces)-1]){
                case 'ALink':
                    $authLinksTemplate .= $this->render->template(ACTIVE_NAV_TEMPLATE.'.aLink',[
                        'route' =>  $this->router->generateURI($authLinks[$i]->getURL(),$authLinks[$i]->getParams()),
                        'avatar' => $authLinks[$i]->getAvatar()
                    ]);
                    break;
                case 'Link':
                    $lData = [
                        'active' => self::isActive($this->router->generateURI($authLinks[$i]->getURL(), $authLinks[$i]->getParams())),
                        'route' => $this->router->generateURI($authLinks[$i]->getURL(),$authLinks[$i]->getParams()),
                        'label' => TRANSLATE?
                            rawurlencode($this->tr::translate($authLinks[$i]->getLabel())):
                            rawurlencode($authLinks[$i]->getLabel()),
                        'icon' => $authLinks[$i]->getIcon(),
                    ];
                    $authLinksTemplate .= $this->render->template(ACTIVE_NAV_TEMPLATE.'.link',$lData);
                    break;
                default:
                    die('wtf');
            }
        }
        return $authLinksTemplate;
    }
    private function buildLink(Link $link): string
    {
        $lData = [
            'active' => self::isActive($this->router->generateURI($link->getURL(), $link->getParams())),
            'route' => $this->router->generateURI($link->getURL(),$link->getParams()),
            'label' => TRANSLATE?
                rawurlencode($this->tr::translate($link->getLabel())):
                rawurlencode($link->getLabel()),
            'icon' => $link->getIcon(),
        ];
        return $this->render->template(ACTIVE_NAV_TEMPLATE.'.link',$lData);
    }
    private function buildDrop(Drop $drop): string
    {
        $active = '';
        $separators = $drop->getSeparator();
        $getSeparator = function()  use(&$separators) {
            if(!empty($separators)){
                return array_shift($separators);
            }
            return false;
        };

        $dropLinksArr = $drop->getLinks();
        $sep =$getSeparator();
        $dropLinks = [];
        for($link=0;$link<count($dropLinksArr);$link++){
            $lData = [
                'active' => self::isActive($this->router->generateURI($dropLinksArr[$link]->getURL(), $dropLinksArr[$link]->getParams())),
                'route' => $this->router->generateURI($dropLinksArr[$link]->getURL(),$dropLinksArr[$link]->getParams()),
                'label' => TRANSLATE?
                    rawurlencode($this->tr::translate($dropLinksArr[$link]->getLabel())):
                    rawurlencode($dropLinksArr[$link]->getLabel()),
                'icon' => $dropLinksArr[$link]->getIcon()
            ];
            $dropLinks[] = $lData;
            if($lData['active'] === ACTIVE_NAV_LINK_CLASS){
                $active = ACTIVE_NAV_LINK_CLASS;
            }
            if($sep){
                if($link === (int)$sep-1){
                    $dropLinks[] = '<a class="dropLnk separator" href=""></a>';
                    $sep =$getSeparator();
                }
            }
        }
        return $this->render->template(ACTIVE_NAV_TEMPLATE.'.dropDown',[
            'dropLinks' => $dropLinks,
            'active' => $active,
            'label' =>  TRANSLATE?
                rawurlencode($this->tr::translate($drop->getLabel())):
                rawurlencode($drop->getLabel())
        ]);
    }
    private function isActive(string $link): string
    {
        if ($link === $this->currentURL) {
            return ACTIVE_NAV_LINK_CLASS;
        }
        return '';
    }
}