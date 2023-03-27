<?php declare(strict_types=1);

namespace PHPSkeleton\Library;

use Latte\Engine;
use PHPSkeleton\Sources\Request;
use PHPSkeleton\Sources\Response;

class TemplateEngine {

    private Engine $latte;
    private array $templateVars = [];
    private string $html = "";

    public function __construct(
        private string $templateDir = ROOT . '/templates',
        private string $cacheDir = ROOT . '/.latte/cache'
    ) {
        $this->latte = new Engine();
        $this->latte->setTempDirectory($this->cacheDir);
        $this->latte->setAutoRefresh($_ENV['MODE'] === 'dev');
    }

    public function assign(array $_vars): void
    {
        $this->templateVars = array_merge($this->templateVars, $_vars);
    }

    /**
     * Use this method to parse a template.
     * 
     * @param string $file The file to be parsed
     * @param string|null $block A defined block within the template (optional)
     * @return string HTML
     */
    public function parse(string $file, string|null $block = null): void
    {
        $this->html = $this->latte->renderToString($this->templateDir . '/' . $file, $this->templateVars, $block);
    }
    
    public function render(Request $request, Response $response): void
    {
        $response->addHeader("Content-Type", "text/html");
        $response->write($this->html);
    }
}