<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

    /**
    * Creates the application.
    *
    * @return \Symfony\Component\HttpKernel\HttpKernelInterface
    */
    public function createApplication()
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }

    public function call($method, $url)
    {
        $this->response = parent::call($method, $url);
    }

    public function get($url)
    {
        $this->call('GET', $url);
    }

    public function assertContentEquals($content, $encode = null)
    {
        $original = $this->response->original;
        if ($encode == null) {
            $original = trim($original);
        } else if ($encode == 'gzip')    {
            try {
                $original = gzdecode($original);
            } catch (\Exception $e) {
                throw new Exception('The content is not gzip encode');
            }
        } else {
            throw new Exception("$encode is not available");
        }
        $this->assertEquals($content, $original);
    }
}
