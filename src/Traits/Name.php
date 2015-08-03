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

        return "->setName('{$this->getName()}')";
    }
}
