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
        $this->dom->loadXML($content);
        return 'xml';
    }

    public function loadHTML($content)
    {
        // Clean
        $content = trim($content);
        $tidy = new \tidy;
        $content = $tidy->repairString($content);

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

        $this->dom->loadHTML($content);
        return 'html';
    }

    public function get($param)
    {
        if (substr($param, 0, 1) == '#') {
            // If element start by a #
            $this->node = $this->getByID($param);
        } else if (substr($param, 0, 1) == '.') {
            // If element start by a .
            $this->node = $this->getByClass($param);
        } else {
            $this->node = $this->getByName($param);
        }

        if ($this->node != null) {
            $this->dom->importNode($this->node);
        } else {
            $this->node = null;
        }
        return $this;
    }

    public function getByName($param)
    {
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[0];

        $nodelist   = $this->dom->getElementsByTagName($param);
        return $this->getNode($nodelist, $nb);
    }

    public function getByID($param)
    {
        $param = str_replace('#', '', $param);
        return $this->dom->getElementByID($param);
    }

    public function getByClass($param)
    {
        $param  = str_replace('.', '', $param);
        $result = $this->getNumber($param);
        $param  = $result[0];
        $nb     = $result[1];

        return $this->query("//*[@class=\"$param\"][$nb]");
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

    public function query($param)
    {
        $xpath    = new \DomXpath($this->dom);
        $nodelist = $xpath->query($param);

        return $this->getNode($nodelist, 0);
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
        if ($name == 'text') {
            return $this->text();
        } else {
            return $this->get($name);
        }
    }
}
