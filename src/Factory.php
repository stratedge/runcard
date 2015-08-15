<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Group;
use Stratedge\Runcard\Middleware;
use Stratedge\Runcard\Route;
use Stratedge\Runcard\RouteCallable;

class Factory
{
    public static function create($data)
    {
        if (!empty($data['routes'])) {
            return new Group($data);
        } else {
            return new Route($data);
        }
    }

    public static function createMiddleware($data)
    {
        return new Middleware($data);
    }

    public static function createCallable($data)
    {
        return new RouteCallable($data);
    }
}
