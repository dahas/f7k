<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;
use f7k\Sources\Request;
use f7k\Sources\Response;

class PermissionDeniedController extends AppController {

    public function __construct(protected Request $request, protected Response $response)
    {
        parent::__construct($request, $response);
    }
    
    #[Route(path: '/PermissionDenied', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            'title' => 'Error 403 - Forbidden'
        ]);
        $this->template->parse('403.partial.html');
        $this->template->render();
    }
}