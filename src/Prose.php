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
        $this->post($slug, 'preview.json');
    }

    public function publish($slug, $releaseNotes = '')
    {
        if ($releaseNotes) {
            $releaseNotes = 'publish[email_readers]=true&publish[release_notes]=' . urlencode($releaseNotes);
        }

        $this->post($slug, 'publish.json', $releaseNotes);
    }

    protected function post($slug, $document, $data = '')
    {
        $key = "api_key={$this->apiKey}";
        $data = ($data) ? $key . "&$data" : $key;
        $url = "https://leanpub.com/$slug/$document";
        $this->requester->request('POST', $url, $data);
    }
}