<?php

namespace Services\Convert;

class Convert
{
    public function toInt($text)
    {
        $text = explode('.', $text)[0];
        $text = preg_replace('/[a-zA-Z\\\#\<\>\/\{\}\,]/', '', $text);
        return (int) $text;
    }

    public function toArray($text)
    {
        $text  = $this->toString($text);
        $array = explode(',', $text);
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
        $text = $this->toInt($text);
        return (float) $text;
    }
}
