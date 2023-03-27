<?php declare(strict_types=1);

namespace PHPSkeleton\Sources;

use ReflectionClass;

class ServiceBase
{
    protected string $table = "";
    protected string $key = "";
    protected string $fields = "";

    public function __construct()
    {
        $rc = new ReflectionClass(get_class($this));
        foreach($rc->getAttributes() as $attribute) {
            foreach($attribute->newInstance() as $name => $property) {
                $this->$name = $property;
            }
        }
    }
}