<?php

namespace f7k\Component;

use f7k\Service\TemplateService;
use f7k\Sources\{ControllerBase, Request, Response};

class MenuComponent extends ControllerBase {

    private string $tmplFile = 'Menu.partial.html';

    public function __construct(
        protected Request $request,
        protected Response $response
    ) {}

    public function getItems(): object
    {
        $json = file_get_contents(ROOT . '/menu.json');
        return (object) json_decode($json);
    }

    public function render(TemplateService $template): string
    {
        $template->assign([
            "nav" => $this->getItems(),
            "currentPath" => "/" . $this->request->getController()
        ]);
        $template->parse($this->tmplFile);
        return $template->getHtml();
    }
}