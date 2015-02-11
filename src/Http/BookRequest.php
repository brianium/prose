<?php
namespace Brianium\Prose\Http;

class BookRequest
{
    protected $requester;

    protected $url;

    protected $apiKey;

    public function __construct($apiKey, $url = 'https://leanpub.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function setHttpRequester(HttoRequesterInterface $requester)
    {
        $this->requester = $requester;
    }

    public function getRequester()
    {
        return $this->requester;
    }

    public function post($slug, $document, $data = '')
    {
        $key = "api_key={$this->apiKey}";
        $file = '';
        $headers = ['Content-Type' => 'application/x-www-form-urlencoded'];

        if (file_exists($data)) {
            $file = $data;
            $headers = ['Content-Type' => 'text/plain'];
        }

        $data = ($data) ? $key . "&$data" : $key;

        $url = "{$this->url}/$slug/$document";

        if ($file) {
            $data = file_get_contents($file);
            $url .= "?api_key={$this->apiKey}";
        }

        return $this->requester->request('POST', $url, $data, $headers);
    }

    public function get($slug, $document = '')
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

    public function getDocument($slug, $document = "")
    {
        $response = $this->get($slug, $document);

        if ($response->isSuccessful()) {
            return json_decode($response->getContent());
        }

        return null;
    }
}