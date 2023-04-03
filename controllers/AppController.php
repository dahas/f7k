<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Component\MenuComponent;
use f7k\Service\TemplateService;
use f7k\Sources\attributes\{Inject, Route};
use f7k\Sources\{Request,Response};
use f7k\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateService::class)]
    protected $template;

    protected array $data;

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct();

        $this->data = $this->request->getData();

        $menu = new MenuComponent($this->request, $this->response);
        
        $this->template->assign([
            "menu" => $menu->render($this->template)
        ]);
    }
}