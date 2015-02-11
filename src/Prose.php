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

        $response = $this->post($slug, 'publish.json', $releaseNotes);

        return $response->isSuccessful();
    }

    public function status($slug)
    {
        $response = $this->get($slug, 'book_status');

        if ($response->isSuccessful()) {
            return json_decode($response->getContent());
        }

        return null;
    }

    public function summary($slug)
    {
        $response = $this->get($slug);

        if ($response->isSuccessful()) {
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

    protected function get($slug, $document = '')
    {
        if (! $document) {
            $slug = $slug . '.json';
        }

        if ($document) {
            $document = "/$document";
        }

        $url = "{$this->url}/{$slug}{$document}?api_key={$this->apiKey}";
        return $this->requester->request('GET', $url);
    }
}