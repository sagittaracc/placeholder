<?php

namespace sagittaracc;

class SimpleList
{
    private $list = [];

    function __construct($list)
    {
        $this->list = $list;
    }

    public static function create($list)
    {
        return new static($list);
    }

    public function apply($str)
    {
        $name = $this->list['name'];
        preg_match('/#'.$name.'\{(.*?)\}/', $str, $matches);
        $pattern = $matches[1];
        $separator = isset($this->list['separator']) ? $this->list['separator'] : '';

        $s = [];
        foreach ($this->list['list'] as $key => $value) {
            $s[] = str_replace(['#key', '#value'], [$key, $value], $pattern);
        }
        $s = implode($separator, $s);

        return str_replace('#' . $name . '{' . $pattern . '}', $s, $str);
    }
}