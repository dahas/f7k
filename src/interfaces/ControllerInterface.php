<?php

namespace PHPSkeleton\Sources\interfaces;

interface ControllerInterface
{
    /**
     * Use to inject a service via an Attribute
     * 
     * @param string $namespace 
     */
    public function injectServices(string $namespace): void;
}