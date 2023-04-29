<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;

class HomeController extends AppController {

    #[Route(path: ['/', '/Home'], method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            "title" => "f7k - yet another framework",
            "keywords" => "PHP, Framework",
            "description" => "f7k - yet another framework",
            'header' => 'f7k - yet another framework',
            "subtitle" => "f7k is the numeronym of the word 'framework'. Use this lightweight framework to quickly build a web application with PHP. If you are unfamiliar or 
            inexperienced with developing secure and high-performant web applications, you better use Symfony, Laravel, or a similar well tested product."
        ]);

        $this->template->parse('Home.partial.html');
        $this->template->render($this->request, $this->response);
    }
}