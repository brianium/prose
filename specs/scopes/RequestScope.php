<?php
use Peridot\Scope\Scope;

class RequestScope extends Scope
{
    private $url;

    private $requester;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function assertRequest($method, $path, $data, $headers = ['Content-Type' => 'application/x-www-form-urlencoded'])
    {
        $url = "{$this->url}$path";
        $this->requester->request($method, $url, $data, $headers)->shouldHaveBeenCalled();
    }

    public function setHttpRequester($requester)
    {
        $this->requester = $requester;
    }
}