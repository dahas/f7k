<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use Opis\ORM\EntityManager;
use PHPSkeleton\Library\{DatabaseLayer as Dbal, JsonAdapter, Navigation, TemplateEngine};
use PHPSkeleton\Sources\ControllerBase;

class AppController extends ControllerBase {

    protected TemplateEngine $template; // Latte Template Engine
    protected JsonAdapter $jsonAdapter;

    public function __construct()
    {
        // Inject Services
        parent::__construct();

        $this->template = new TemplateEngine();
        $this->template->assign([
            "nav" => Navigation::items()
        ]);

        $this->jsonAdapter = new JsonAdapter();
    }
}