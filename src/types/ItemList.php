<?php

namespace sagittaracc;

class ItemList
{
    private $itemList = [];

    function __construct($itemList)
    {
        $this->itemList = $itemList;
    }

    public static function create($itemList)
    {
        return new static($itemList);
    }

    public function apply($str)
    {
        $name = $this->itemList['name'];
        preg_match('/#'.$name.'\((.*?)\)/', $str, $matches);
        $pattern = $matches[1];
        $separator = isset($this->itemList['separator']) ? $this->itemList['separator'] : '';

        $s = [];
        foreach ($this->itemList['list'] as $item) {
            $s[] = str_replace(array_keys($item), array_values($item), $pattern);
        }
        $s = implode($separator, $s);

        return str_replace('#' . $name . '(' . $pattern . ')', $s, $str);
    }
}