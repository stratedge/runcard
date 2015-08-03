<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Traits\Middleware as MW;
use Stratedge\Runcard\Traits\Name;
use Stratedge\Runcard\Traits\Nesting;
use Stratedge\Runcard\Traits\Uri;

class Group
{
    use Uri;
    use Nesting;
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

        $this->setNesting($nesting);
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
        $children = implode("\n\n", $this->getChildren());

        $output = $this->buildGroup($children);
        $output .= $this->buildMiddleware();
        $output .= ';';

        return $this->formatNesting($output);
    }

    public function buildGroup($children)
    {
        return <<<"EOT"
\$app->group('{$this->getUri()}', function () {
$children
})
EOT;
    }
}
