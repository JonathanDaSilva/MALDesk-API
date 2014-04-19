<?php
namespace Services\Dom;

class Dom {

    private $dom;
    private $node = null;

    public function __construct($dom = null)
    {
        if ($dom != null) {
            $this->dom = $dom;
        } else {
            $this->dom = new \DomDocument;
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
            $this->getByID($name);
        } else if ($param[0] == '.') {
            $this->getByClass($name);
        } else {
            $this->getByName($param);
        }
        return $this;
    }

    public function getByID($param)
    {
        $this->node = $this->dom->getElementByID($param);
        $this->importNode();
        return $this;
    }

    public function query($param)
    {
        $xpath      = new \DomXpath($this->dom);
        $nodelist   = $xpath->query($param);
        $this->node = $this->getNode($nodelist, 0);
        $this->importNode();
        return $this;
    }

    public function getByClass($param)
    {
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[1]+1;

        $this->query("//*[@class=\"$param\"][$nb]");
        return $this;
    }

    public function getByName($param)
    {
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[1];

        $nodelist   = $this->dom->getElementsByTagName($param);
        $this->node = $this->getNode($nodelist, $nb);
        $this->importNode();
        return $this;
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

    public function getNode($nodelist, $nb)
    {
        if ($nodelist->length >= $nb) {
            return $nodelist->item($nb);
        } else {
            return null;
        }
    }

    public function importNode()
    {
        if($this->node != null) {
            $this->dom->importNode($this->node);
        }
        return $this;
    }

    public function text()
    {
        if ($this->node != null) {
            return $this->node->textContent;
        } else {
            return false;
        }
    }

    public function __get($name)
    {
        if ($name === 'text') {
            return $this->text();
        } else {
            return $this->get($name);
        }
    }
}
