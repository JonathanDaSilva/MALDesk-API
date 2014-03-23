<?php

class DomTest extends TestCase
{
    public $xml;
    public $html;

    public function setUp()
    {
        parent::setUp();
        $dir = dirname(__FILE__);
        $this->xml  = file_get_contents($dir.'/example/xinillist.xml');
        $this->html = file_get_contents($dir.'/example/anime-6213.html');
    }

    public function testLoadXML()
    {
        $this->assertEquals('xml', Dom::loadXML($this->xml));
        $this->assertEquals('xml', Dom::load($this->xml));
    }

    public function testLoadHtml()
    {
        $this->assertEquals('html', Dom::loadHTML($this->html));
        $this->assertEquals('html', Dom::load($this->html));
    }

    public function testTagName()
    {
        // HTML
        $this->assertEquals('html', Dom::load($this->html));
        $this->assertContains('Kagaku no Railgun', Dom::get('h1')->text);

        // XML
        $this->assertEquals('xml', Dom::load($this->xml));
        $this->assertEquals('Cowboy Bebop', Dom::get('anime')->series_title->text);

        // If doesn't exist
        $this->assertFalse(Dom::get('anime')->foo->text);
    }

    public function testTagNameWithNumber()
    {
        $this->assertEquals('html', Dom::load($this->html));
        $this->assertContains('Synopsis', Dom::get('h2[5]')->text);
    }

    public function testByID()
    {
        $this->assertEquals('html', Dom::load($this->html));
        $this->assertContains('Watching', Dom::get('#myinfo_status')->text);
    }

    public function testByXpath()
    {
        $this->assertEquals('html', Dom::load($this->html));
        $this->assertContains('Ranked', Dom::query('//*[@class="spaceit"]')->text);
    }

    public function testByClass()
    {
        $this->assertEquals('html', Dom::load($this->html));
        $this->assertContains('Watching', Dom::get('.spaceit')->text);
        $this->assertContains('Watching', Dom::get('.spaceit[5]')->text);
    }
}
