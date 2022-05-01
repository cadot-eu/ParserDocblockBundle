<?php

namespace Cadoteu\ParserDocblockBundle;

class ParserDocblock
{
    /** @var \ReflectionProperty $property */
    protected $property;
    private $alias = ['choice'];
    public function __construct(\ReflectionProperty $property)
    {
        $this->property = $property;
    }
    function getOptions()
    {
        $options = [];
        $docs = $this->property->getDocComment();
        $tab = explode("\n", $docs);
        if (strpos($tab[1], ':') === false && !in_array($this->clean($tab[1]), $this->alias)) return  $this->clean($tab[1]);
        foreach (explode("\n", $docs) as $doc) {
            if ($this->clean($doc) != '') {
            }
        }
        return $options;
    }
    function getAlias(): string
    {
        if ($this->property->getName() == 'id') return '';
        $docs = $this->property->getDocComment();
        $tab = explode("\n", $docs);
        if (strpos($tab[1], ':') === false && in_array($this->clean($tab[1]), $this->alias)) return  $this->clean($tab[1]);
        //Dans les autres cas
        return '';
    }
    function getType(): string
    {
        return isset($this->property->getAttributes()[0]) && isset($this->property->getAttributes()[0]->getArguments()['type']) ? $this->property->getAttributes()[0]->getArguments()['type'] : '';
    }

    function clean($string): string
    {
        return trim($string, " \t\n\r\0\x0B*\/\\");
    }
}
