<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Traits\Middleware as MW;
use Stratedge\Runcard\Traits\Name;
use Stratedge\Runcard\Traits\Indent;
use Stratedge\Runcard\Traits\Uri;

class Route
{
    use Uri;
    use Name;
    use Indent;
    use MW;

    protected $method;
    protected $callable;
    protected $for_group = false;

    public function __construct($data, $nesting = 0)
    {
        if (!empty($data['uri'])) {
            $this->setUri($data['uri']);
        }

        if (!empty($data['method'])) {
            $this->setMethod($data['method']);
        }

        if (!empty($data['callable'])) {
            $this->addCallable($data['callable']);
        }

        if (!empty($data['name'])) {
            $this->setName($data['name']);
        }

        if (!empty($data['middleware'])) {
            foreach ($data['middleware'] as $middleware) {
                $this->addMiddleware($middleware);
            } 
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getCallable()
    {
        return $this->callable;
    }

    public function hasCallable()
    {
        return !is_null($this->getCallable());
    }

    public function addCallable($callable)
    {
        $this->callable = Factory::createCallable($callable);
    }

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

    public function __toString()
    {
        $route = $this->buildRoute();

        $middleware = $this->buildMiddleware();
        
        $name = $this->buildName();

        $post_pieces = array_merge($middleware, [$name]);
        $post_pieces = array_filter($post_pieces);

        if (count($post_pieces) > 0) {
            $post_pieces = $this->formatPostPieces($post_pieces);
        }

        $parts = array_filter([$route, implode("\n", $post_pieces)]);

        $output = implode($this->hasCallable() ? "\n" : null, $parts);
        $output .= ';';
        
        return $output;
    }

    public function buildRoute()
    {
        if ($this->hasCallable() === false) {
            return $this->buildPlaceholder();
        } else {
            return $this->buildCallable();
        }
    }

    public function formatPostPieces($post_pieces)
    {
        if ($this->hasCallable() == false) {
            $first = array_shift($post_pieces);
        }

        if ($this->hasCallable() == true) {
            if ($this->getForGroup() == true) {
                $indent = 5;
            } else {
                $indent = 4;
            }
        } else {
            $indent = 2;
        }

        foreach ($post_pieces as &$piece) {
            $piece = $this->indent($piece, $indent);
        }

        if ($this->hasCallable() == false) {
            array_unshift($post_pieces, $first);
        }

        return $post_pieces;
    }

    public function buildPlaceholder()
    {
        return <<<"EOT"
->{$this->getMethod()}('{$this->getURI()}', function (\$request, \$response, \$args) {
    //Add route functionality here
    return \$response;
})
EOT;
    }

    public function buildCallable()
    {
        return <<<"EOT"
->{$this->getMethod()}('{$this->getURI()}', {$this->getCallable()})
EOT;
    }
}
