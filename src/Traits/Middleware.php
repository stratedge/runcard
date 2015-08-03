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

    public function addMiddleware($data)
    {
        $this->middleware[] = Factory::createMiddleware($data);
    }

    public function buildMiddleware()
    {
        $middleware = [];

        foreach ($this->getMiddleware() as $mw) {
            $middleware[] = (string) $mw;
        }

        return $middleware;
    }
}