<?php

namespace f7k\Sources\interfaces;

interface ServiceInterface
{
    /**
     * Injects a service via an Attribute
     */
    public function injectServices(): void;
}