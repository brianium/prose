<?php
namespace Brianium\Prose;

use Brianium\Prose\Http\HttpRequesterInterface;

class Prose
{
    protected $requester;

    protected $apiKey;

    protected $url;

    public function __construct($apiKey, $url = 'https://leanpub.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function setHttpRequester(HttpRequesterInterface $requester)
    {
        $this->requester = $requester;
    }

    public function preview($slug, $file = "")
    {
        $document = 'preview.json';
        $data = "api_key={$this->apiKey}";

        if (file_exists($file)) {
            $document = "single.json?$data";
            $data = file_get_contents($file);
        }

        $this->requester->request('POST', "{$this->url}/$slug/$document", $data);
    }

    public function subset($slug)
    {
        $this->post($slug, 'subset.json');
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
        $url = "{$this->url}/$slug/$document";
        $this->requester->request('POST', $url, $data);
    }
}