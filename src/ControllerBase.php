<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\traits\Injection;

class ControllerBase  {

    use Injection;

    protected array $data;
    protected Session $session;

    public function __construct(protected Request $request, protected Response $response)
    {
        $this->data = $this->request->getData();

        $this->session = new Session();

        $this->triggerServiceInjection($this->request, $this->response, $this->session);
    }
}