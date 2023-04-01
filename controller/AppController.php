<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Library\{MenuService, TemplateService};
use f7k\Sources\attributes\Inject;
use f7k\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Inject(MenuService::class)]
    protected $menu;

    public function __construct()
    {
        parent::__construct();
        
        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "currentPath" => $_SERVER['REQUEST_URI']
        ]);
    }
}