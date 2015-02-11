<?php
namespace Brianium\Prose\Http;

class Response
{
    protected $status;

    protected $content;

    public function __construct($status, $content)
    {
        $this->status = $status;
        $this->content = $content;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function isSuccessful()
    {
        return $this->status >= 200 && $this->status < 400;
    }
}