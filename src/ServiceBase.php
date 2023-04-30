<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\traits\Injection;

class ServiceBase  {

    use Injection;

    public function __construct(protected Request $request, protected Response $response, protected Session $session)
    {
        $this->triggerServiceInjection($this->request, $this->response, $this->session);
    }
}