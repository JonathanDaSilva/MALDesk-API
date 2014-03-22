<?php
namespace Services\Http;

class Http {

    public $userAgent;

    public function __construct()
    {
        $this->userAgent = \Config::get('app.userAgent');
    }

    public function get($url)
    {
        $response = \cURL::newRequest('GET', $url)
                    ->setHeaders([
                        'User-Agent'      => $this->userAgent,
                        'Accept-Encoding' => 'gzip',
                    ])
                    ->send();

        if (
            array_key_exists('Content-Encoding', $response->headers) &&
            $response->headers['Content-Encoding'] == 'gzip'
        ) {
            $response->body = gzdecode($response->body);
        }

        return $response->body;
    }
}
