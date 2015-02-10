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

    public function publish($slug, $releaseNotes = '')
    {
        $data = "api_key={$this->apiKey}";

        if ($releaseNotes) {
            $data .= '&publish[email_readers]=true&publish[release_notes]=' . urlencode($releaseNotes);
        }

        $this->requester->request('POST', "https://leanpub.com/$slug/publish.json", $data);
    }
}