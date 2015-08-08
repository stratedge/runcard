<?php
namespace Stratedge\Runcard\Abstracts;

abstract class Invokable
{
    use \Stratedge\Runcard\Traits\ParseTemplate;

    protected $invokable;

    public function __construct($invokable)
    {
        $this->setTemplatePath(__DIR__ . '/../Templates/');

        if (is_array($invokable) == true) {

            if (
                isset($invokable['class']) == true &&
                isset($invokable['method']) == true &&
                is_string($invokable['class']) == true &&
                is_string($invokable['method']) == true
            ) {
                //Class-type invokable, class will be in quotes
                $this->setInvokableClass($invokable['class'], $invokable['method']);
            } else if (
                isset($invokable['object']) == true &&
                isset($invokable['method']) == true &&
                is_string($invokable['object']) == true &&
                is_string($invokable['method']) == true
            ) {
                //Object-type invokable, class will not be in quotes
                $this->setInvokableObject($invokable['object'], $invokable['method']);
            } else if (
                isset($invokable['static']) == true &&
                isset($invokable['method']) == true &&
                is_string($invokable['static']) == true &&
                is_string($invokable['method']) == true
            ) {
                //Static-type invokable, class and method together in one string in quotes
                $this->setInvokableStatic($invokable['static'], $invokable['method']);
            } else if (
                isset($invokable[0]) == true &&
                isset($invokable[1]) == true &&
                is_string($invokable[0]) == true &&
                is_string($invokable[1]) == true
            ) {
                //Numerically-keyed array, assume an object-type invokable
                $this->setInvokableObject($invokable[0], $invokable[1]);
            } else if (
                isset($invokable['function']) == true &&
                is_string($invokable['function']) == true
            ) {
                //Function-type invokable, whole thing in quotes
                $this->setInvokableFunction($invokable['function']);
            } else {
                //Problem!
            }

        } else if (is_string($invokable) == true) {
            $this->setInvokable($invokable);
        } else {
            //Use the placeholder version of the invokable
        }
    }

    public function getInvokable()
    {
        return $this->invokable;
    }

    public function hasInvokable()
    {
        return is_null($this->invokable) === false;
    }

    protected function setInvokable($invokable)
    {
        return $this->invokable = $invokable;
    }

    public function setInvokableClass($class, $method)
    {
        $this->setInvokable(
            $this->parseTemplate('invokable.class.tpl', [
                '$class' => $class,
                '$method' => $method
            ])
        );
    }

    public function setInvokableObject($object, $method)
    {
        $this->setInvokable(
            $this->parseTemplate('invokable.object.tpl', [
                '$object' => $object,
                '$method' => $method
            ])
        );
    }

    public function setInvokableStatic($static, $method)
    {
        $this->setInvokable(
            $this->parseTemplate('invokable.static.tpl', [
                '$static' => $static,
                '$method' => $method
            ])
        );
    }

    public function setInvokableString($string)
    {
        $this->setInvokable(
            $this->parseTemplate('invokable.string.tpl', [
                '$string' => $string
            ])
        );
    }

    public function setInvokableFunction($function)
    {
        $this->setInvokableString($function);
    }

    public function __toString()
    {
        if ($this->hasInvokable()) {
            $str = $this->parseInvokable();
        } else {
            $str = $this->parsePlaceholder();
        }

        return $str;
    }

    abstract public function parseInvokable();

    abstract public function parsePlaceholder();
}