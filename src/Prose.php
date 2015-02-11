<?php
namespace Brianium\Prose;

use Brianium\Prose\Http\Request;
use Brianium\Prose\Http\Response;

class Prose
{
    protected $request;

    protected $apiKey;

    protected $url;

    public function __construct($apiKey, $url = 'https://leanpub.com')
    {
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function preview($slug, $file = "")
    {
        $document = 'preview.json';
        $data = "";

        if (file_exists($file)) {
            $document = "single.json";
            $data = $file;
        }

        $response = $this->request->post($slug, $document, $data);
        return $response->isSuccessful();
    }

    public function subset($slug)
    {
        $response = $this->request->post($slug, 'subset.json');
        return $response->isSuccessful();
    }

    public function publish($slug, $releaseNotes = '')
    {
        if ($releaseNotes) {
            $releaseNotes = 'publish[email_readers]=true&publish[release_notes]=' . urlencode($releaseNotes);
        }

        $response = $this->request->post($slug, 'publish.json', $releaseNotes);

        return $response->isSuccessful();
    }

    public function status($slug)
    {
        return $this->request->getDocument($slug, 'book_status');
    }

    public function summary($slug)
    {
        return $this->request->getDocument($slug);
    }

    public function sales($slug)
    {
        return $this->request->getDocument($slug, 'sales.json');
    }
}