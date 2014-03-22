<?php
namespace Services\Basic;

class Basic {

    private $connected;
    private $basic    = null;
    private $username = null;
    private $password = null;

    public function __construct()
    {
        $this->basic     = \Request::header('Authorization');
        $this->connected = $this->basic != null;

        if ($this->connected) {
            $decode = str_replace('Basic ', '', $this->basic);
            $decode = base64_decode($decode);
            $decode = explode(':', $decode);
            $this->username = $decode[0];
            $this->password = $decode[1];
        }
    }

    public function isNotConnected()
    {
        return !$this->connected;
    }

    public function isConnected()
    {
        return $this->connected;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

}
