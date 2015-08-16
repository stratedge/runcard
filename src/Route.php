<?php
namespace Stratedge\Runcard;

class Route
{
    use \Stratedge\Runcard\Traits\ForGroup;
    use \Stratedge\Runcard\Traits\Indent;
    use \Stratedge\Runcard\Traits\Middleware;
    use \Stratedge\Runcard\Traits\Name;
    use \Stratedge\Runcard\Traits\ParseTemplate;
    use \Stratedge\Runcard\Traits\Uri;

    protected $method;
    protected $callable;

    public function __construct($data)
    {
        $this->setTemplatePath(__DIR__ . '/Templates/');

        if (!empty($data['uri'])) {
            $this->setUri($data['uri']);
        }

        if (!empty($data['method'])) {
            $this->setMethod($data['method']);
        }

        if (!empty($data['callable'])) {
            $this->addCallable($data['callable']);
        } else {
            $this->addCallable(null);
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
        return !is_null($this->getCallable()->getInvokable());
    }

    public function addCallable($callable)
    {
        $this->callable = Factory::createCallable($callable);
    }

    public function __toString()
    {
        $route = $this->buildRoute();

        $post_route = $this->buildPostRoute();

        $parts = array_filter([$route, implode("\n", $post_route)]);

        $glue = $this->hasCallable() ? "\n" : null;

        $output = implode($glue, $parts) . ';';
        
        return $output;
    }

    public function buildRoute()
    {
        return $this->parseTemplate('route.structure.tpl', [
            '$method' => $this->getMethod(),
            '$uri' => $this->getURI(),
            '$callable' => $this->getCallable()
        ]);
    }

    public function buildPostRoute()
    {
        $post = array_filter(
            array_merge(
                $this->buildMiddleware(),
                (array) $this->buildName()
            )
        );

        if (empty($post)) {
            return $post;
        }

        return $this->formatPostPieces($post);
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
}
