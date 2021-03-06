<?php
namespace Stratedge\Runcard\Traits;

trait Indent
{
    public function indent($output, $indent = 0)
    {
        $lines = explode("\n", $output);
        
        array_walk($lines, function (&$line) use ($indent) {
            //Pad the line with the given number of spaces
            $line = str_pad('', $indent) . $line;
        });

        return implode("\n", $lines);
    }
}
