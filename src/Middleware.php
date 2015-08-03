<?php
namespace Stratedge\Runcard;

use Stratedge\Runcard\Traits\Nesting;

class Middleware
{
    // use Nesting;

    protected $value;

    public function __construct($value)
    {
        $this->setValue($value);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return $this->buildMiddleware();
    }

    public function buildMiddleware()
    {
        if (is_null($this->getValue())) {
            $output = $this->buildPlaceholder();
        } else {
            $output = $this->buildValue();
        }

        return $output;
    }

    public function buildPlaceholder()
    {
        return <<<"EOT"
->add(function (\$request, \$response, \$next) {
    return \$next(\$request, \$response);
})
EOT;
    }

    public function buildValue()
    {
        return <<<"EOT"
->add({$this->getValue()})
EOT;
    }
}
