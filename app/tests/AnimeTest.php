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

    public function testGetAnime()
    {
        Http::shouldReceive('get')
            ->atLeast()
            ->once()
            ->andReturn($this->html);

        $response = Anime::get(6213)['serie'];

        $this->assertEquals('Toaru Kagaku no Railgun',      $response['title']);
        $this->assertEquals('A Certain Scientific Railgun', $response['title_eng']);
        $this->assertEquals('Toaru Kagaku no Choudenjihou', $response['synonyms']);
        $this->assertEquals(525,                            $response['ranked']);
        $this->assertEquals("TV",                           $response['type']);
        $this->assertEquals(24,                             $response['episodes']);
        $this->assertEquals(2,                              $response['status']);
        $this->assertContains("Action",                     $response['genres']);
        $this->assertEquals(7.96,                           $response['score']);
        $this->assertEquals(
            "Academy City is a highly developed place in terms of technology. It is said to be 20 to 30 years ahead of the rest of the world, and 80% of its 2.3 million residents are students. The focus of studies here is directed towards esper powers. Misaka Mikoto, one of the top level espers in town, shares a room with Kuroko Shirai, another high level esper who is a member of Judgement, a law enforcing agency composed of students. Both attend Tokiwadai, a private school reserved for the high-leveled and the rich. Kuroko's partner at Judgement, Kazari Uiharu, is a low level esper who studies at Sakugawa middle school. Her best friend and classmate there is Ruiko Saten, a level zero, one who has no esper powers. Together, the four encounter several adventures in the exciting scientific town. (Source: ANN)",
            $response['synopsis']
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }
}
