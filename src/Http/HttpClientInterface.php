<?php
namespace Brianium\Prose\Http;

interface HttpClientInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param string $data
     * @param array $headers
     * @return Response
     */
    public function request($method, $url, $data, array $headers);
}