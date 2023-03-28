<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Sources\attributes\Route;
use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;

class IndexController extends AppController {

    #[Route(path: '/', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            'title' => 'PHP Skeleton',
            'header' => 'Yet another f7k',
            "subtitle" => "f7k is the numeronym of the word 'framework'. Use this lightweight framework to quickly build feature rich web applications with PHP. If you are unfamiliar or 
            inexperienced with developing secure and high-performance web applications, I strongly recommend using Symfony, Laravel, or a similar well tested product."
        ]);
        $this->template->parse('Index.partial.html');
        $this->template->render($request, $response);
    }
}