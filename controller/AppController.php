<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\{JsonAdapter, Navigation, TemplateEngine};
use PHPSkeleton\Sources\attributes\Inject;
use PHPSkeleton\Sources\ControllerBase;

class AppController extends ControllerBase {

    #[Inject(TemplateEngine::class)]
    protected $template;

    #[Inject(JsonAdapter::class)]
    protected $jsonAdapter;

    public function __construct()
    {
        parent::__construct(); // Trigger injection of Services

        $this->template->assign([
            "nav" => Navigation::items()
        ]);
    }
}