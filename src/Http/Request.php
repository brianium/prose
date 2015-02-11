<?php
namespace Brianium\Prose\Http;

class Request
{
    protected $client;

    protected $url;

    protected $apiKey;

    public function __construct($apiKey, $url = 'https://leanpub.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function setHttpClient(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getHttpClient()
    {
        return $this->client;
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

        return $this->client->request('POST', $url, $data, $headers);
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
        return $this->client->request('GET', $url);
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