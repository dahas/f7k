<?php declare(strict_types=1);

namespace f7k\Sources\exceptions;

use f7k\Sources\interfaces\InvalidArgumentExceptionInterface;


class InvalidArgumentException extends \Exception implements InvalidArgumentExceptionInterface {

    public function __construct($message, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
