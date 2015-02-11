<?php
namespace Brianium\Prose;

use Brianium\Prose\Http\HttpRequesterInterface;
use Brianium\Prose\Http\Response;

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
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        if (file_exists($file)) {
            $document = "single.json?$data";
            $data = file_get_contents($file);
            $headers = ['Content-Type' => 'text/plain'];
        }

        $response = $this->requester->request('POST', "{$this->url}/$slug/$document", $data, $headers);
        return $response->isSuccessful();
    }

    public function subset($slug)
    {
        $response = $this->post($slug, 'subset.json');
        return $response->isSuccessful();
    }

    public function publish($slug, $releaseNotes = '')
    {
        if ($releaseNotes) {
            $releaseNotes = 'publish[email_readers]=true&publish[release_notes]=' . urlencode($releaseNotes);
        }

        $this->post($slug, 'publish.json', $releaseNotes);
    }

    public function status($slug)
    {
        $response = $this->requester->request('GET', "{$this->url}/$slug/book_status?api_key={$this->apiKey}");
        $status = $response->getStatus();

        if ($status >= 200 && $status < 400) {
            return json_decode($response->getContent());
        }

        return null;
    }

    protected function post($slug, $document, $data = '')
    {
        $key = "api_key={$this->apiKey}";
        $data = ($data) ? $key . "&$data" : $key;
        $url = "{$this->url}/$slug/$document";
        return $this->requester->request('POST', $url, $data, ['Content-Type' => 'application/x-www-form-urlencoded']);
    }
}