<?php
namespace Brianium\Prose;

class Prose
{
    protected $requester;

	public function setHttpRequester($requester)
	{
        $this->requester = $requester;
	}

	public function preview($slug)
	{
        $this->requester->request('POST', "https://leanpub.com/$slug/preview.json", 'api_key=12345');
	}
}