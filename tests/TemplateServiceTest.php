<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use f7k\Service\TemplateService;
use f7k\Sources\Response;
use f7k\Sources\Request;
use f7k\Sources\Session;

!defined('ROOT') && define('ROOT', dirname(__DIR__, 1));

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->safeLoad();

class TemplateServiceTest extends TestCase {
    
    private Request $request;
    private Response $response;
    private TemplateService $template;

    protected function setUp(): void
    {
        $_ENV['LAYOUT_TEMPLATE_NAME'] = "Layout.html";

        $this->request = new Request();
        $this->response = new Response();

        $this->template = new TemplateService($this->request, $this->response, new Session);
        $this->template->setTemplateDir(__DIR__ . '/files');
        $this->template->setCacheDir(__DIR__ . '/cache/templates');
    }

    protected function tearDown(): void
    {
        unset($this->request);
        unset($this->response);
        unset($this->template);

        // Clear cache folder:
        array_map('unlink', array_filter((array) glob(__DIR__ . "/cache/templates/*")));
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