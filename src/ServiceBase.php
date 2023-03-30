<?php declare(strict_types=1);

namespace PHPSkeleton\Sources;

use PHPSkeleton\Sources\interfaces\ServiceInterface;
use ReflectionClass;

class ServiceBase implements ServiceInterface {

    /**
     * Use of a ReflectionClass to inject Services assigned to Attributes.
     * 
     * @param string $namespace 
     */
    public function injectServices(): void
    {
        $rc = new ReflectionClass(get_class($this));
        $properties = $rc->getProperties();
        foreach ($properties as $property) {
            $pName = $property->name;
            $attributes = $property->getAttributes();
            foreach ($attributes as $attribute) {
                $instance = $attribute->newInstance();
                $service = $instance->service;
                $options = $instance->options;
                $this->$pName = new $service(options: $options);
            }
        }
    }
}