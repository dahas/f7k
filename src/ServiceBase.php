<?php declare(strict_types=1);

namespace f7k\Sources;

use f7k\Sources\traits\Injection;


class ServiceBase {

    use Injection;

    public function __construct()
    {
        $this->triggerServiceInjection();
    }
}