<?php
namespace Brianium\Prose\Http;

interface HttpRequesterInterface
{
    /**
     * @param string $method
     * @param string $url
     * @param string $data
     * @param array $headers
     */
    public function request($method, $url, $data, array $headers);
}