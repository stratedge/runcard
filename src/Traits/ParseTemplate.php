<?php
namespace Stratedge\Runcard\Traits;

trait ParseTemplate
{
    protected $template_path;

    public function getTemplatePath()
    {
        return $this->template_path;
    }

    public function setTemplatePath($template_path)
    {
        $this->template_path = $template_path;
        return $this;
    }

    public function parseTemplate($path, array $replacements = [])
    {
        $template = file_get_contents($this->getTemplatePath() . $path);

        return strtr($template, $replacements);
    }
}
