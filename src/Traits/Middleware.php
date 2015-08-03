<?php
namespace Stratedge\Runcard\Traits;

use Stratedge\Runcard\Factory;

trait Middleware
{
    protected $middleware = [];

    public function getMiddleware()
    {
        return $this->middleware;
    }

    public function addMiddleware($data, $nesting = 0)
    {
        $this->middleware[] = Factory::createMiddleware($data, $nesting);
    }

    public function buildMiddleware()
    {
        return implode($this->getMiddleware());
    }
}