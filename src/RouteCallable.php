<?php
namespace Stratedge\Runcard;

class RouteCallable extends \Stratedge\Runcard\Abstracts\Invokable
{
    public function parseInvokable()
    {
        return $this->getInvokable();
    }

    public function parsePlaceholder()
    {
        return $this->parseTemplate('route_callable.placeholder.tpl');
    }
}
