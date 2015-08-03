<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Traits\Middleware as MW;
use Stratedge\Runcard\Traits\Name;
use Stratedge\Runcard\Traits\Nesting;
use Stratedge\Runcard\Traits\Uri;

class Route
{
    use Uri;
    use Name;
    use Nesting;
    use MW;

    protected $method;
    protected $callable;

    public function __construct($data, $nesting = 0)
    {
        if (!empty($data['uri'])) {
            $this->setUri($data['uri']);
        }

        if (!empty($data['method'])) {
            $this->setMethod($data['method']);
        }

        if (!empty($data['callable'])) {
            $this->setCallable($data['callable']);
        }

        if (!empty($data['name'])) {
            $this->setName($data['name']);
        }

        if (!empty($data['middleware'])) {
            foreach ($data['middleware'] as $middleware) {
                $this->addMiddleware(
                    $middleware,
                    $this->hasCallable() ? 1 : 0
                );
            } 
        }

        $this->setNesting($nesting);
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

    public function setCallable($callable)
    {
        $this->callable = $callable;
    }

    public function __toString()
    {
        $route = $this->buildRoute();
        $middleware = $this->buildMiddleware();
        $name = $this->buildName();
        
        $parts = array_filter([$route, $middleware, $name]);

        $output = implode($this->hasCallable() ? "\n" : null, $parts);
        $output .= ';';
        
        return $this->formatNesting($output);
    }

    public function buildRoute()
    {
        if ($this->hasCallable() === false) {
            return $this->buildPlaceholder();
        } else {
            return $this->buildCallable();
        }
    }

    public function buildPlaceholder()
    {
        return <<<"EOT"
\$app->{$this->getMethod()}('{$this->getURI()}', function (\$request, \$response, \$args) {
    //Add route functionality here
    return \$response;
})
EOT;
    }

    public function buildCallable()
    {
        return <<<"EOT"
\$app->{$this->getMethod()}('{$this->getURI()}', {$this->getCallable()})
EOT;
    }
}
