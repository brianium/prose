<?php
namespace Brianium\Prose;

use Brianium\Prose\Http\HttpRequesterInterface;

class Prose
{
    protected $requester;

    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function setHttpRequester(HttpRequesterInterface $requester)
    {
        $this->requester = $requester;
    }

    public function preview($slug)
    {
        $this->requester->request('POST', "https://leanpub.com/$slug/preview.json", "api_key={$this->apiKey}");
    }
}