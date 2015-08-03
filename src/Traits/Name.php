<?php
namespace Stratedge\Runcard\Traits;

trait Name
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function buildName()
    {
        if (is_null($this->name)) {
            return null;
        }

        $output = "->setName('{$this->getName()}')";

        return $this->hasCallable() ? str_pad('', 4, ' ') . $output : $output;
    }
}
