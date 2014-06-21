<?php

namespace Services\Convert;

class Convert
{
    public function toInt($text)
    {
        return (int) $this->toFloat($text);
    }

    public function toArray($text, $explode=null)
    {
        if ($explode == null) {
            $explode = ',';
        }
        $text  = $this->toString($text);
        $array = explode($explode, $text);
        $array = array_filter($array, function(&$element){
            $element = trim($element);
            return $element;
        });
        $array = array_unique($array);
        $array = array_values($array);
        return (array) $array;
    }

    public function toString($text)
    {
        $text = preg_replace('/\s+/', ' ', $text);
        return (string) $text;
    }

    public function toFloat($text)
    {
        $text = preg_replace('/[a-zA-Z\\\#\<\>\/\{\}\,]/', '', $text);
        return (float) $text;
    }
}
