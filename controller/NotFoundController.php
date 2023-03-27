<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\TemplateEngine;
use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;

class NotFoundController extends AppController {
    
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            'title' => 'Error 404 - Page Not Found'
        ]);
        $this->template->parse('404.partial.html');
        $this->template->render($request, $response);
    }
}