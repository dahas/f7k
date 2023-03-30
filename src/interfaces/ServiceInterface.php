<?php

namespace PHPSkeleton\Sources\interfaces;

interface ServiceInterface
{
    /**
     * Use to inject a service via an Attribute
     */
    public function injectServices(): void;
}