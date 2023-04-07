<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Service\MenuService;
use f7k\Service\TemplateService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request,Response};
use f7k\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    #[Inject(MenuService::class)]
    protected $menu;

    protected array $data;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();

        $this->data = $this->request->getData();
        
        $this->template->assign([
            "nav" => $this->menu->getItems(),
            "currentPath" => "/" . $this->request->getSegments()[0]
        ]);
        $this->template->parse('Menu.partial.html');
    }
}