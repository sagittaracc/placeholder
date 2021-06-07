<?php

namespace sagittaracc;

class Map
{
    private $map = [];

    function __construct($map)
    {
        $this->map = $map;
    }

    public static function create($map)
    {
        return new static($map);
    }

    public function apply($str)
    {
        foreach ($this->map as $key => $value) {
            $str = preg_replace("/\{\{$key\}\}/", $value, $str);
        }

        return $str;
    }
}