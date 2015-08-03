<?php
namespace Stratedge\Runcard\Traits;

trait Uri
{
    protected $uri;

    public function getUri()
    {
        return $this->uri;
    }

    public function setUri($uri)
    {
        $this->uri = $uri;
    }
}