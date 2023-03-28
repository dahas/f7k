<?php declare(strict_types=1);

namespace PHPSkeleton\Sources;

use PHPSkeleton\Sources\interfaces\ControllerInterface;
use ReflectionClass;

class ControllerBase implements ControllerInterface {

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
            foreach ($property->getAttributes() as $attribute) {
                $service = $attribute->newInstance()->service;
                $this->$pName = new $service;
            }
        }
    }
}