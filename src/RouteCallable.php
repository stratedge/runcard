<?php
namespace Stratedge\Runcard;

class RouteCallable
{
    protected $callable;

    public function __construct($callable)
    {
        if (is_array($callable) == true) {

            if (
                isset($callable['class']) == true &&
                isset($callable['method']) == true &&
                is_string($callable['class']) == true &&
                is_string($callable['method']) == true
            ) {
                //Class-type callable, class will be in quotes
                $this->setCallableClass($callable['class'], $callable['method']);
            } else if (
                isset($callable['object']) == true &&
                isset($callable['method']) == true &&
                is_string($callable['object']) == true &&
                is_string($callable['method']) == true
            ) {
                //Object-type callable, class will not be in quotes
                $this->setCallableObject($callable['object'], $callable['method']);
            } else if (
                isset($callable['static']) == true &&
                isset($callable['method']) == true &&
                is_string($callable['static']) == true &&
                is_string($callable['method']) == true
            ) {
                //Static-type callable, class and method together in one string in quotes
                $this->setCallableStatic($callable['static'], $callable['method']);
            } else if (
                isset($callable[0]) == true &&
                isset($callable[1]) == true &&
                is_string($callable[0]) == true &&
                is_string($callable[1]) == true
            ) {
                //Numerically-keyed array, assume an object-type callable
                $this->setCallableObject($callable[0], $callable[1]);
            } else if (
                isset($callable['function']) == true &&
                is_string($callable['function']) == true
            ) {
                //Function-type callable, whole thing in quotes
                $this->setCallableFunction($callable['function']);
            } else {
                //Problem!
            }

        } else if (is_string($callable) == true) {
            $this->setCallable($callable);
        } else {
            //Problem!
        }
    }

    public function getCallable()
    {
        return $this->callable;
    }

    protected function setCallable($callable/*, $method = null*/)
    {
        return $this->callable = $callable;
    }

    public function setCallableClass($class, $method)
    {
        $this->setCallable(
            "['$class', '$method']"
        );
    }

    public function setCallableObject($object, $method)
    {
        $this->setCallable(
            "[$object, '$method']"
        );
    }

    public function setCallableStatic($static, $method)
    {
        $this->setCallable(
            "'$static::$method'"
        );
    }

    public function setCallableString($string)
    {
        $this->setCallable(
            "'$string'"
        );
    }

    public function setCallableFunction($function)
    {
        $this->setCallableString($function);
    }

    public function __toString()
    {
        return $this->getCallable();
    }
}