<?php
/**************************************************************************
Copyright 2018 Benato Denis
Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at
http://www.apache.org/licenses/LICENSE-2.0
Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
 *****************************************************************************/

namespace APIHackful\Client;

use APIHackful\DataEncoderTrait;
use APIHackful\DataDecoderTrait;

class RESTfulCaller
{
    use DataEncoderTrait;
    use DataDecoderTrait;

    protected $method = 'GET';

    protected $uri = '/';

    protected $server = 'http://example.org';

    protected $outData = [];

    protected $inData = [];

    public function &withURI($uri)
    {
        if ((!is_string($uri)) || (strlen($uri) <= 0)) {
            throw new \RuntimeException('Invalid URI');
        }

        $this->uri = $uri;

        return $this;
    }

    public function &withServer($server)
    {
        if ((!is_string($server)) || (strlen($server) <= 0)) {
            throw new \RuntimeException('Invalid URI');
        }

        $this->server = trim($server, "\t\n\0/");

        return $this;
    }

    public function &withMethod($method)
    {
        if ((!is_string($method)) || (strlen($method) <= 0)) {
            throw new \RuntimeException('Invalid HTTP Method');
        }

        $this->method = strtoupper($method);

        return $this;
    }

    public function &withInputData(array $data = [])
    {
        $this->inData = $data;

        return $this;
    }

    public function getMethod()
    {
        return "{$this->method}";
    }

    public function getURI()
    {
        return "{$this->uri}";
    }

    public function getServer()
    {
        return "{$this->server}";
    }

    public function getInput() : array
    {
        return $this->inData;
    }

    public function getOutput() : array
    {
        return $this->outData;
    }

    public function execute()
    {
        //prepare the API call
        $handler = curl_init($this->getServer() . $this->getURI());
        curl_setopt_array($handler, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 1000,
            CURLOPT_TIMEOUT => 1000,
            CURLOPT_CUSTOMREQUEST => $this->getMethod(),
        ));

        //prepare content
        if (($this->getMethod() == "POST") || ($this->getMethod() == "PUT")) {
            curl_setopt(
                $handler,
                CURLOPT_POSTFIELDS,
                $sentData = static::pack($this->getInput())
            );
        }

        //perform the RESTful request
        $result = curl_exec($handler);

        //check and report errors
        if ($errNo = curl_errno($handler)) {
            $error_message = curl_strerror($errNo);

            throw new \RuntimeException("Unable to perform the RESTful API Call: {$error_message}");
        }

        //an empty response is not valid!
        if (strlen($result) == 0) {
            throw new \RuntimeException("The RESTful API Call response is empty");
        }

        curl_close($handler);

        //decode the API result
        static::unpack($result);
    }
}