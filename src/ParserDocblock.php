<?php

namespace Cadoteu\ParserDocblockBundle;

class ParserDocblock
{
    /**
     * @var \ReflectionProperty
     */
    protected $property;

    private $alias = ['choice'];

    public function __construct(\ReflectionProperty $property)
    {
        $this->property = $property;
    }

    public function getOptions()
    {
        $options = [];
        $docs = $this->property->getDocComment();
        $tab = explode("\n", $docs);
        //Return an option that is not an alias
        if (strpos($tab[1], ':') === false && !in_array($this->clean($tab[1]), $this->alias)) {
            return $this->clean($tab[1]);
        }
        foreach (explode("\n", $docs) as $doc) {
            //si on a une valeur
            if ($this->clean($doc) != '') {
                //We look if we have an action and value
                if (($deb = strpos($doc, ':')) === false) {
                    $options[$this->clean(substr($doc, 0, $deb))] = $this->clean($doc);
                } else {
                    //merge or create
                    if (isset($options[$this->clean(substr($doc, 0, $deb))]))
                        $options[$this->clean(substr($doc, 0, $deb))] =  json_encode(array_merge(json_decode($options[$this->clean(substr($doc, 0, $deb))], true), json_decode(substr($doc, $deb + 1), JSON_UNESCAPED_SLASHES)));
                    else
                        $options[$this->clean(substr($doc, 0, $deb))] = substr($doc, $deb + 1);
                }
            }
        }
        return $options;
    }

    public function getAlias(): string
    {
        if ($this->property->getName() == 'id') {
            return '';
        }
        $docs = $this->property->getDocComment();
        $tab = explode("\n", $docs);
        if (strpos($tab[1], ':') === false && in_array($this->clean($tab[1]), $this->alias)) {
            return $this->clean($tab[1]);
        }
        //Dans les autres cas
        return '';
    }

    public function getType(): string
    {
        return isset($this->property->getAttributes()[0]) && isset($this->property->getAttributes()[0]->getArguments()['type']) ? $this->property->getAttributes()[0]->getArguments()['type'] : '';
    }

    public function clean($string): string
    {
        return trim($string, " \t\n\r\0\x0B*\/\\");
    }
}
