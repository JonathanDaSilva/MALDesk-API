<?php

class HttpTest extends TestCase {

    public function testUserAgent()
    {
        Config::shouldReceive('get')
            ->with('app.userAgent')
            ->andReturn('foo');

        $http = new \Services\Http\Http;

        $this->assertEquals('foo', $http->userAgent);
    }

    public function testIfReturnContent()
    {
        $response = Mockery::mock('response');
        $response->body    = 'bar';
        $response->headers = [];

        cURL::shouldReceive('newRequest->setHeaders->send')
            ->andReturn($response);

        $this->assertEquals('bar', Http::get('foo'));
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

        $this->assertEquals('bar', Http::get('foo'));
    }
}
