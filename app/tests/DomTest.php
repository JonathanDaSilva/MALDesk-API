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
        $this->assertTrue(Dom::loadXML($this->xml));
        $this->assertTrue(Dom::load($this->xml));
    }

    public function testLoadHtml()
    {
        $this->assertTrue(Dom::loadHTML($this->html));
        $this->assertTrue(Dom::load($this->html));
    }

    public function testTagName()
    {
        // HTML
        Dom::load($this->html);
        $this->assertContains('Alternative Title', Dom::get('h2')->text);

        // XML
        Dom::load($this->xml);
        $this->assertEquals('Cowboy Bebop', Dom::get('anime')->series_title->text);

        // If doesn't exist
        $this->assertFalse(Dom::get('anime')->foo->text);
    }

    public function testTagNameWithNumber()
    {
        Dom::load($this->html);
        $this->assertContains('Synopsis', Dom::get('h2[5]')->text);
    }

    public function testByID()
    {
        Dom::load($this->html);
        $this->assertContains('Login', Dom::get('#malLogin')->text);
    }

    public function testByXpath()
    {
        Dom::load($this->html);
        $this->assertContains('Episodes', Dom::query('//*[@class="spaceit"]')->text);
    }

    public function testByClass()
    {
        Dom::load($this->html);
        $this->assertContains('Episodes', Dom::get('.spaceit')->text);
        $this->assertContains('Members', Dom::get('.spaceit[5]')->text);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
