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
                //Explictly set callable by class and method
                $this->setCallable($callable['class'], $callable['method']);
            } else if (
                isset($callable[0]) == true &&
                isset($callable[1]) == true &&
                is_string($callable[0]) == true &&
                is_string($callable[1]) == true
            ) {
                //Numerically indexed array
                $this->setCallable($callable[0], $callable[1]);
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

    public function setCallable($callable, $method = null)
    {
        if (!is_null($method)) {
            $this->callable = "[$callable, '$method']";
        } else {
            $this->callable = $callable;
        }
    }

    public function __toString()
    {
        return $this->getCallable();
    }
}