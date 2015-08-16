<?php
namespace Stratedge\Runcard\Traits;

trait ForGroup
{
    protected $for_group = false;

    public function getForGroup()
    {
        return $this->for_group;
    }

    public function setForGroup($bool = false)
    {
        $this->for_group = $bool;
    }

    public function forGroup()
    {
        $this->setForGroup(true);
        return $this;
    }
}
