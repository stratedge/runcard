<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Group;
use Stratedge\Runcard\Middleware;
use Stratedge\Runcard\Route;

class Factory
{
    public static function create($data, $nesting = 0)
    {
        if (!empty($data['routes'])) {
            return new Group($data, $nesting);
        } else {
            return new Route($data, $nesting);
        }
    }

    public static function createMiddleware($data, $nesting = 0)
    {
        return new Middleware($data, $nesting);
    }
}
