<?php declare(strict_types=1);

namespace PHPSkeleton\Controller;

use PHPSkeleton\Library\JsonAdapter;
use PHPSkeleton\Sources\attributes\Route;
use PHPSkeleton\Sources\attributes\Inject;
use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;


class BlogController extends AppController
{
    #[Inject('DataService')]
    protected $DataService;

    #[Inject('UserService')] 
    protected $UserService;

    #[Route(path: '/Blog', method: 'get')]
    public function main(Request $request, Response $response): void
    {
        $this->template->assign([
            'title' => 'Blog',
            'header' => 'Why you\'ll be amazed',
            "subtitle" => "Read on ..."
        ]);
        $this->template->parse('Blog.partial.html');
        $this->template->render($request, $response);
    }


    #[Route(path: '/Blog/load', method: 'get')]
    public function load(Request $request, Response $response) : void
    {
        $svcs = [
            'DataService' => $this->DataService->loadData(),
            'UserService' => $this->UserService->loadData()
        ];

        $adapter = new JsonAdapter();
        $adapter->setMessage("Success");
        $adapter->setData($svcs);
        $adapter->encode($request, $response);
    }
}
