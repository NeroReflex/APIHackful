<?php


namespace APIHackful\Client;

use APIHackful\DataEncoderTrait;
use APIHackful\DataDecoderTrait;

class RESTfulCaller
{
    use DataEncoderTrait;
    use DataDecoderTrait;

    protected $method = 'GET';

    public function &withMethod($method)
    {
        if ((!is_string($method)) || (strlen($method) <= 0)) {
            throw new \RuntimeException('Invalid HTTP Method');
        }



        return $this;
    }

    public function execute()
    {

    }
}