<?php

namespace PHPSkeleton\Sources\interfaces;

interface ControllerInterface
{
    /**
     * Use to inject a service via an Attribute
     */
    public function injectServices(): void;
}