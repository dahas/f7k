<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Library\TemplateEngine;
use f7k\Sources\Request;
use f7k\Sources\Response;

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