<?php

namespace sagittaracc;

class PlaceholderHelper
{
    private $str;
    private $quote;
    private $openParenthesis;
    private $closeParenthesis;

    function __construct($str = '')
    {
        $this->setString($str);
        $this->setQuote("'");
        $this->setParenthesis('[', ']');
    }

    public function setString($str)
    {
        if (is_string($str))
            $this->str = $str;

        return $this;
    }

    public function stringOfChar($count, $char)
    {
        $this->setString(implode(',', array_fill(1, $count, $char)));
        return $this;
    }

    public function setQuote($quote)
    {
        if (is_string($quote))
            $this->quote = $quote;

        return $this;
    }

    public function setParenthesis($openParenthesis, $closeParenthesis)
    {
        $this->openParenthesis = $openParenthesis;
        $this->closeParenthesis = $closeParenthesis;

        return $this;
    }

    public function bind()
    {
        $params = func_get_args();
        $numArgs = func_num_args();

        if ($numArgs === 1 && is_array($params[0]) && !ArrayHelper::isSequential($params[0])) {
            $this->bindByNamed($params[0]);
        }
        else {
            $this->bindByUnNamed($params);
        }

        return $this->str;
    }

    private function bindByNamed($param)
    {
        foreach ($param as $placeholder => $value) {
            $this->str = str_replace($placeholder, $this->format($value), $this->str);
        }
    }

    private function bindByUnNamed($params)
    {
        foreach ($params as $param) {
            $this->str = preg_replace('/\?/', $this->format($param), $this->str, 1);
        }
    }

    public function bindObject($object)
    {
        return $object->apply($this->str);
    }

    private function format($param)
    {
        switch (gettype($param)) {
            case 'boolean':
                return $param ? 'true' : 'false';
            case 'integer':
            case 'double':
                return $param;

            case 'string':
                return "{$this->quote}$param{$this->quote}";

            case 'array':
                return ArrayHelper::isSequential($param)
                    ? $this->openParenthesis . implode(',', array_map(array($this, 'format'), $param)) . $this->closeParenthesis
                    : '#array';

            case 'NULL':
                return 'NULL';

            default:
                return '';
        }
    }
}
