<?php declare(strict_types=1);

namespace f7k\Controller;

use f7k\Sources\attributes\Route;

class ExampleMdController extends AppController {

    #[Route(path: '/Example/Markdown', method: 'get')]
    public function main(): void
    {
        $this->template->assign([
            "title" => "Markdown Service",
            "h1" => "# This is a h1 header",
            "h2" => "## This is a h2 header",
            "h3" => "### This is a h3 header",
            "code" => '````
// Content of the "Markdown.partial.html" template:

{layout \'App.layout.html\'}

{block content}
<div class="container-sm mt-3">
    {$h1|markdown|noescape}  
    {$h2|markdown|noescape}  
    {$h3|markdown|noescape} 
</div> 
{/block}
````',
        ]);
        $this->template->parse("Markdown.partial.html");
        $this->template->render();
    }
}