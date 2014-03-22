<?php

class HttpTest extends TestCase {

    public function testUserAgent()
    {
        Config::shouldReceive('get')
            ->with('app.userAgent')
            ->andReturn('foo');

        $http = new \Services\Http\Http;

        $this->assertEquals($http->userAgent, 'foo');
    }

    public function testIfReturnContent()
    {
        $response = Mockery::mock('response');
        $response->body    = 'bar';
        $response->headers = [];

        cURL::shouldReceive('newRequest->setHeaders->send')
            ->andReturn($response);

        $this->assertEquals(Http::get('foo'),  'bar');
    }

    public function testEncodingContent()
    {
        $response = Mockery::mock('response');
        $response->body    = gzencode('bar');
        $response->headers = [
            'Content-Encoding' => 'gzip',
        ];

        cURL::shouldReceive('newRequest->setHeaders->send')
            ->andReturn($response);

        $this->assertEquals(Http::get('foo'),  'bar');
    }
}
