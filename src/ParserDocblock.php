<?php

namespace Cadoteu\ParserDocblockBundle;

class ParserDocblock
{

    protected $comment;

    public function __construct($comment)
    {
        $this->comment = trim($comment, " \t\n\r\0\x0B*");
    }

    public function getCleaned()
    {
        return $this->comment;
    }
    public function getType()
    {
        if (substr($this->comment, 0, 1) == '@') return null;
        return isset(explode('=', $this->comment)[0]) ? trim(explode('=', $this->comment)[0]) : null;
    }
    public function getVar()
    {
        if (substr($this->comment, 0, 1) == '@') return null;
        return isset(explode('=', $this->comment)[1]) ? trim(explode('=', $this->comment)[1]) : null;
    }
    public function getVal()
    {
        return isset(explode('=>', $this->comment)[1]) ? trim(explode('=>', $this->comment)[1]) : null;
    }
}
