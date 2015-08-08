<?php
namespace Stratedge\Runcard;

class Group
{

    use \Stratedge\Runcard\Traits\Indent;
    use \Stratedge\Runcard\Traits\Middleware;
    use \Stratedge\Runcard\Traits\ParseTemplate;
    use \Stratedge\Runcard\Traits\Uri;

    protected $children;

    public function __construct($data, $nesting = 0)
    {
        $this->setTemplatePath(__DIR__ . '/Templates/');

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
        $middleware = $this->buildMiddleware();

        if (count($middleware) > 1) {
            $first = array_shift($middleware);

            foreach ($middleware as &$mw) {
                $mw = $this->indent($mw, 2);
            }

            array_unshift($middleware, $first);
        }

        $middleware = implode("\n", $middleware);

        $output = $this->buildGroup();
        $output .= $middleware;
        $output .= ';';

        return $output;
    }

    public function buildChildren()
    {
        $children = [];

        foreach ($this->getChildren() as $child) {
            $children[] = $this->indent(
                '$this' . $child->forGroup(),
                4
            );
        }

        return implode("\n\n", $children);
    }

    public function buildGroup()
    {
        return $this->parseTemplate('group.structure.tpl', [
            '$children' => $this->buildChildren()
        ]);
    }
}
