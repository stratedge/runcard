<?php
namespace Stratedge\Runcard;

class Middleware extends \Stratedge\Runcard\Abstracts\Invokable
{
    public function parseInvokable()
    {
        return $this->parseTemplate('middleware.invokable.tpl', [
            '$invokable' => $this->getInvokable()
        ]);
    }

    public function parsePlaceholder()
    {
        return $this->parseTemplate('middleware.placeholder.tpl');
    }
}
