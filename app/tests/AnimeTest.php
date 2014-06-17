<?php

class AnimeTest extends TestCase
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

    public function testGetList()
    {
        Http::shouldReceive('get')
            ->atLeast()
            ->once()
            ->andReturn($this->xml);

        $response = Anime::getList('xinil');

        // ID
        $this->assertEquals(1,  $response[0]['serie']['id']);
        $this->assertEquals(15, $response[1]['serie']['id']);
        // Title
        $this->assertEquals('Cowboy Bebop',  $response[0]['serie']['title']);
        $this->assertEquals('Eyeshield 21',  $response[1]['serie']['title']);
        $this->assertContains('Eyeshield21', $response[1]['serie']['synonyms']);
        // Episode
        $this->assertEquals(26,  $response[0]['serie']['episodes']);
        $this->assertEquals(145, $response[1]['serie']['episodes']);
        // Type
        $this->assertEquals(1, $response[0]['serie']['type']);
        $this->assertEquals(1, $response[1]['serie']['type']);
        // Status
        $this->assertEquals(2, $response[0]['serie']['status']);
        $this->assertEquals(2, $response[1]['serie']['status']);
        // Status
        $this->assertContains("images/anime", $response[0]['serie']['image']);
        $this->assertContains("images/anime", $response[1]['serie']['image']);

        // Watched
        $this->assertEquals(26, $response[0]['user']['episodes']);
        $this->assertEquals(51, $response[1]['user']['episodes']);
        // Score
        $this->assertEquals(10, $response[0]['user']['score']);
        $this->assertEquals(7,  $response[1]['user']['score']);
        // Score
        $this->assertEquals(2, $response[0]['user']['status']);
        $this->assertEquals(3, $response[1]['user']['status']);
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
