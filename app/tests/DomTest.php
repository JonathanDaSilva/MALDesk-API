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
        $this->assertEquals('xml', Dom::type());
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
        $this->assertEquals('Cowboy Bebop', Dom::get('anime[1]')->series_title->text);
        $this->assertEquals('Eyeshield 21', Dom::get('anime[2]')->series_title->text);

        // If doesn't exist
        $this->assertFalse(Dom::get('anime')->foo->text);
    }

    public function testTagNameWithNumber()
    {
        Dom::load($this->html);
        $this->assertContains('Synopsis', Dom::get('h2[6]')->text);
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
        $this->assertContains('Episodes', Dom::query('//*[@class="spaceit"][1]')->text);
        $this->assertContains('Members',  Dom::query('//*[@class="spaceit"][6]')->text);
    }

    public function testByClass()
    {
        Dom::load($this->html);
        $this->assertContains('Episodes', Dom::get('.spaceit')->text);
        $this->assertContains('Episodes', Dom::get('.spaceit[1]')->text);
        $this->assertContains('Members',  Dom::get('.spaceit[6]')->text);
    }

    public function testLoop()
    {
        Dom::load($this->xml);
        $i = 0;
        Dom::get('anime')->each(function($anime) use(&$i){
            if ($i == 0) {
                $this->assertEquals('Cowboy Bebop', $anime->series_title->text);
            } else {
                $this->assertEquals('Eyeshield 21', $anime->series_title->text);
            }
            $i++;
        });

        $this->assertEquals(2, $i);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
