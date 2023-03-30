<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\{JsonAdapter, Navigation, TemplateEngine};
use PHPSkeleton\Sources\attributes\Inject;
use PHPSkeleton\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(service: TemplateEngine::class)]
    protected $template;

    #[Inject(service: Navigation::class)]
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