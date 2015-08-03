<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Traits\Middleware as MW;
use Stratedge\Runcard\Traits\Indent;
use Stratedge\Runcard\Traits\Uri;

class Group
{
    use Uri;
    use Indent;
    use MW;

    protected $children;

    public function __construct($data, $nesting = 0)
    {
        if (!empty($data['uri'])) {
            $this->setUri($data['uri']);
        }

        foreach ($data['routes'] as $route_data) {
            $this->addChild($route_data);
        }

        if (!empty($data['middleware'])) {
            foreach($data['middleware'] as $middleware) {
                $this->addMiddleware($middleware);
            }
        }
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function addChild($data)
    {
        $this->children[] = Factory::create($data, 1);
    }

    public function __toString()
    {
        $children = $this->buildChildren();

        foreach ($children as &$child) {
            $child = $this->indent($child, 4);
        }

        $middleware = $this->buildMiddleware();

        if (count($middleware) > 1) {
            $first = array_shift($middleware);

            foreach ($middleware as &$mw) {
                $mw = $this->indent($mw, 2);
            }

            array_unshift($middleware, $first);
        }

        $middleware = implode("\n", $middleware);

        $output = $this->buildGroup($children);
        $output .= $middleware;
        $output .= ';';

        return $output;
    }

    public function buildChildren()
    {
        $children = [];

        foreach ($this->getChildren() as $child) {
            $children[] = '$this' . $child->forGroup();
        }

        return $children;
    }

    public function buildGroup($children)
    {
        $children = implode("\n\n\n", $children);

        return <<<"EOT"
->group('{$this->getUri()}', function () {

$children

})
EOT;
    }
}
