<?php

class BasicTest extends TestCase {

    public $basic;

    public function setUp()
    {
        parent::setUp();
        $encode      = base64_encode("foo:bar");
        $this->basic = "Basic $encode";
    }

    public function testWithCredentials()
    {
        Request::shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn($this->basic);

        $this->assertTrue(Basic::isConnected());
        $this->assertFalse(Basic::isNotConnected());
        $this->assertEquals('foo', Basic::getUsername());
        $this->assertEquals('bar', Basic::getPassword());
    }

    public function testNoWithCredentials()
    {
        Request::shouldReceive('header')
            ->with('Authorization')
            ->once()
            ->andReturn(null);

        $this->assertFalse(Basic::isConnected());
        $this->assertTrue(Basic::isNotConnected());
        $this->assertNull(Basic::getUsername());
        $this->assertNull(Basic::getPassword());
    }
}
