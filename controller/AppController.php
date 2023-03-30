<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Library\{Navigation, TemplateEngine};
use f7k\Sources\attributes\Inject;
use f7k\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateEngine::class)]
    protected $template;

    #[Inject(Navigation::class)]
    protected $navigation;

    public function __construct()
    {
        $this->injectServices();

        $this->template->assign([
            "nav" => $this->navigation->items(),
            "currentPath" => $_SERVER['REQUEST_URI']
        ]);
    }
}