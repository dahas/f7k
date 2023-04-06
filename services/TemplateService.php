<?php declare(strict_types=1);

namespace f7k\Service;

use f7k\Service\MarkdownService;
use f7k\Sources\attributes\Inject;
use f7k\Sources\ServiceBase;
use Latte\Engine;
use f7k\Sources\Request;
use f7k\Sources\Response;

class TemplateService extends ServiceBase {

    #[Inject(MarkdownService::class)]
    protected $markdown;

    private Engine $latte;
    private array $templateVars = [];
    private string $html = "";

    public function __construct(
        private string $templateDir = ROOT . '/templates',
        private string $cacheDir = ROOT . '/.latte/cache',
        private array|null $options = []
    ) {
        parent::__construct();

        $this->latte = new Engine();
        $this->latte->setTempDirectory($this->cacheDir);
        $this->latte->setAutoRefresh($_ENV['MODE'] === 'dev');

        $this->latte->addFilter('markdown', fn(string $s) => $this->markdown->parse($s));
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
    
    public function getHtml(): string
    {
        return $this->html;
    }
    
    public function render(Request $request, Response $response): void
    {
        $response->addHeader("Content-Type", "text/html");
        $response->write($this->html);
    }
}