<?php
namespace Stratedge\Runcard\Traits;

trait Nesting
{
    protected $nesting = 0;

    public function getNesting()
    {
        return $this->nesting;
    }

    public function setNesting($nesting)
    {
        $this->nesting = $nesting;
    }

    public function formatNesting($output)
    {
        $lines = explode("\n", $output);
        
        array_walk($lines, [$this, 'formatNestingLine']);

        return implode("\n", $lines);
    }

    public function formatNestingLine(&$line)
    {
        $line = str_pad('', 4 * $this->getNesting()) . $line;
    }
}
