<?php

class ApiTest extends TestCase
{
    public function testIfReturnList()
    {
        Basic::shouldReceive('getUsername')
            ->atLeast()
            ->once()
            ->andReturn('foo');

        Anime::shouldReceive('getList')
            ->atLeast()
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $this->get('/api/animelist');
        $this->assertContentEquals('bar');
    }

    public function testIfReturnListWithUsername()
    {
        Anime::shouldReceive('getList')
            ->atLeast()
            ->once()
            ->with('foo')
            ->andReturn('bar');

        $this->get('/api/animelist/foo');
        $this->assertContentEquals('bar');
    }
}
