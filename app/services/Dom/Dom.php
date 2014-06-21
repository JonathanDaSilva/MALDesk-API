<?php
namespace Services\Dom;

class Dom {

    private $dom      = null;
    private $node     = null;
    private $nodelist = null;

    public function __construct($nodelist = null, $node = null)
    {
        $this->dom = new \DomDocument;
        if ($node != null) {
            $node = $this->dom->importNode($node, true);
            $this->dom->appendChild($node);
            $this->nodelist = $nodelist;
            $this->node     = $node;
        }
    }

    public function load($content)
    {
        $content = trim($content);
        if (preg_match('/^\<\?xml.*/', $content)) {
            return $this->loadXML($content);
        } else if (preg_match('/^\<\!doctype.*/i', $content)) {
            return $this->loadHTMl($content);
        } else {
            return false;
        }
    }

    public function loadXML($content)
    {
        $content = trim($content);
        try {
            $this->dom->loadXML($content);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function loadHTML($content)
    {
        // Clean
        $content = trim($content);
        $content = preg_replace('/\<h1\>\<div.*\>.*\<\/div\>(.*)\<\/h1\>/', '<h1>$1</h1>', $content);
        $content = \Purifier::clean($content);

        // Delete duplicate ids
        $ids = [];
        $content = preg_replace_callback(
            '/id\s*=\s*"\s*(\w*)\s*"/',
            function ($value) use (&$ids){
                foreach ($ids as $id) {
                    if ($id == $value[1]) {
                        return "";
                    }
                }
                array_push($ids, $value[1]);
                return $value[0];
            },
            $content
        );

        try {
            $this->dom->loadHTML($content);
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    public function get($param)
    {
        $name = substr($param, 1);
        if ($param[0] == '#') {
            $this->getById($name);
        } else if ($param[0] == '.') {
            $this->getByClass($name);
        } else {
            $this->getByName($param);
        }
        return $this->getDom();
    }

    public function getById($param)
    {
        $this->node     = $this->dom->getElementByID($param);
        $this->nodelist = null;
        return $this->getDom();
    }

    public function getByName($param)
    {
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[1]-1;

        if ($nb < 0) {
            $nb = 0;
        }

        $this->nodelist = $this->dom->getElementsByTagName($param);
        $this->node     = $this->getNode($nb);
        return $this->getDom();
    }

    public function getByClass($param)
    {
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[1];

        $query = "//*[@class=\"$param\"]";
        if($nb != 0) {
            $query = $query."[$nb]";
        }


        $this->query($query);
        return $this->getDom();
    }

    public function query($param)
    {
        $xpath          = new \DomXpath($this->dom);
        $this->nodelist = $xpath->evaluate($param);
        $this->node     = $this->getNode(0);
        return $this->getDom();
    }

    public function getNumber($param)
    {
        $nb = 0;
        // If the user specify a number
        $param = preg_replace_callback(
            '/\[(\d+)\]/',
            function ($value) use (&$nb) {
                $nb = (int) $value[1];
                return '';
            },
            $param
        );
        return [$param, $nb];
    }

    public function getNode($nb)
    {
        if ($this->nodelist == null) {
            return null;
        } else if ($this->nodelist->length >= $nb) {
            return $this->nodelist->item($nb);
        } else {
            return null;
        }
    }

    public function getDom($node = null)
    {
        if ($node == null) {
            $node = $this->node;
        }
        return new self($this->nodelist, $node);
    }

    public function text()
    {
        if ($this->node != null) {
            return $this->node->textContent;
        } else {
            return false;
        }
    }

    public function content()
    {
        $result = "";
        if ($this->node != null) {
            $lists  = $this->node->childNodes;
            for ($i=0; $i < $lists->length; $i++){
                $node = $lists->item($i);
                if ($node->nodeName == '#text') {
                    $result = $result.$node->textContent;
                }
            }
        }
        return trim($result);
    }

    public function each($callback)
    {
        if( $this->nodelist == null) {
            return null;
        }

        for($i = 0; $i < $this->nodelist->length; $i++) {
            $node = $this->nodelist->item($i);
            $dom  = $this->getDom($node);
            $callback($dom);
        }
    }

    public function __get($name)
    {
        switch($name) {
            case 'text':
                return $this->text();
                break;
            case 'content':
                return $this->content();
                break;
            default:
                return $this->get($name);
        }
    }
}
