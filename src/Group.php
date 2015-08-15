<?php
namespace Stratedge\Runcard;

class Group
{

    use \Stratedge\Runcard\Traits\Indent;
    use \Stratedge\Runcard\Traits\Middleware;
    use \Stratedge\Runcard\Traits\ParseTemplate;
    use \Stratedge\Runcard\Traits\Uri;

    protected $children;

    public function __construct($data)
    {
        $this->setTemplatePath(__DIR__ . '/Templates/');

        if (!empty($data['uri'])) {
            $this->setUri($data['uri']);
        }

        //@todo Throw exception if routes property is not set or empty
        
        $inheritable_data = [];

        if (!empty($data['callable'])) {
            $inheritable_data['callable'] = $data['callable'];
        }
        
        //Attach each child route or group to this group
        foreach ($data['routes'] as $route_data) {
            $this->addChild(array_merge_recursive($inheritable_data, $route_data));
        }

        //Attach each middleware to this group
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
        $this->children[] = Factory::create($data);
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
            '$uri' => $this->getURI(),
            '$children' => $this->buildChildren()
        ]);
    }
}
