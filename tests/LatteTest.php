<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPSkeleton\Library\TemplateEngine;
use PHPSkeleton\Sources\Response;
use PHPSkeleton\Sources\Request;

!defined('ROOT') && define('ROOT', dirname(__DIR__, 1));

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();

class LatteTest extends TestCase
{
    private Request $request; 
    private Response $response; 
    private TemplateEngine $template; 
 
    protected function setUp() : void
    {
        $_ENV['LAYOUT_TEMPLATE_NAME'] = "Layout.html";

        $this->request = new Request();
        $this->response = new Response();
        $this->template = new TemplateEngine(__DIR__ . '/files', __DIR__ . '/cache');
    }
 
    protected function tearDown() : void
    {
        unset($this->request);
        unset($this->response);
        unset($this->template);
    }
    
    public function testParse()
    {
        $this->template->assign([
            "title" => "John Rambo",
            "text" => "Something escaped",
            "header" => "John Rambo",
            "var" => "Partial content",
            "list" => [
                "aaa" => "Alpha",
                "bbb" => "Beta",
                "ccc" => "Gamma",
            ]
        ]);
        $this->template->parse("Partial.html");
        $this->template->render($this->request, $this->response);

        $this->assertStringEqualsFile(__DIR__ . "/files/result.html", $this->response->read());
    }
}