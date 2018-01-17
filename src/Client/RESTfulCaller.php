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

/**
 * The APIHackful client implementation.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
class RESTfulCaller
{
    use DataEncoderTrait;
    use DataDecoderTrait;

    /**
     * @var string the HTTP Method
     */
    protected $method = 'GET';

    /**
     * @var string the URI of the resource
     */
    protected $uri = '/';

    /**
     * @var string the HTTP or HTTPS server
     */
    protected $server = 'http://example.org';

    /**
     * @var array|null the received data
     */
    protected $outData = null;

    /**
     * @var array data to be sent to the server on the request
     */
    protected $inData = [];

    /**
     * @var string the encryption key
     */
    protected $key = "";

    /**
     * Set the encryption key for the current request.
     *
     * Setting the encryption key changes the default key,
     * which is the APIHACKFUL_ENCRYPTION_KEY macro.
     *
     * @param string $key the new base64 encoded encryption key (at least 32 bytes)
     * @return $this the current request for easy chaining
     * @throws \InvalidArgumentException the given key is not valid
     */
    public function &withEncryptionKey($key = "")
    {
        if (!is_string($key)) {
            throw new \InvalidArgumentException("");
        }

        $this->key = $key;

        return $this;
    }

    /**
     * Set the URI of the resource to be queried.
     *
     * @param string $uri the resource URI
     * @return $this the current request for easy chaining
     * @throws \InvalidArgumentException the given URI is not valid
     */
    public function &withURI($uri)
    {
        if ((!is_string($uri)) || (strlen($uri) <= 0)) {
            throw new \InvalidArgumentException('Invalid URI');
        }

        $this->uri = $uri;

        return $this;
    }

    /**
     * Set the server of the resource to be queried.
     *
     * The server must be specified with its protocol: HTTP or HTTPS.
     *
     * @param string $server the resource server
     * @return $this the current request for easy chaining
     * @throws \InvalidArgumentException the given server is not valid
     */
    public function &withServer($server)
    {
        if ((!is_string($server)) || (strlen($server) <= 0)) {
            throw new \InvalidArgumentException('Invalid URI');
        }

        $this->server = trim($server, "\t\n\0\r\x0B /");

        return $this;
    }

    /**
     * Set the HTTP method to be used to query the server.
     *
     * @param string $method the HTTP method to be used
     * @return $this the current request for easy chaining
     * @throws \InvalidArgumentException the given method is not valid
     */
    public function &withMethod($method)
    {
        if ((!is_string($method)) || (strlen($method) <= 0)) {
            throw new \InvalidArgumentException('Invalid HTTP Method');
        }

        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Set the data for the request.
     *
     * The provided data may be unused if the method is NOT a  POST or PUT.
     *
     * @param array $data the input data collection
     * @return $this the current request for easy chaining
     */
    public function &withInputData(array $data = [])
    {
        $this->inData = $data;

        return $this;
    }

    /**
     * Get the HTTP method.
     *
     * @return string the HTTP method
     */
    public function getMethod()
    {
        return "{$this->method}";
    }

    /**
     * Get the URI of the resource
     *
     * @return string the resource URI
     */
    public function getURI()
    {
        return "{$this->uri}";
    }

    /**
     * Get the API server.
     *
     * @return string the API server
     */
    public function getServer()
    {
        return "{$this->server}";
    }

    /**
     * Get the data to be used as the input on the API call.
     *
     * @return array the call input data
     */
    public function getInput() : array
    {
        return $this->inData;
    }

    /**
     * Get the output of the API call.
     *
     * @return array the result of the API call
     * @throws \RuntimeException the output data is not available
     */
    public function getOutput() : array
    {
        if (is_null($this->outData)) {
            throw new \RuntimeException("Output data is not available before API call execution");
        }

        return $this->outData;
    }

    /**
     * Perform the current API call.
     *
     * This function encodes the provided input data,
     * performs the request with it and decodes the result.
     *
     * @throws \RuntimeException the error preventing data decode
     */
    public function execute()
    {
        //prepare the API call
        $handler = curl_init($this->getServer() . $this->getURI());
        curl_setopt_array($handler, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_CUSTOMREQUEST => $this->getMethod(),
        ));

        //prepare content
        if (($this->getMethod() == "POST") || ($this->getMethod() == "PUT")) {
            curl_setopt(
                $handler,
                CURLOPT_POSTFIELDS,
                $sentData = static::pack($this->getInput(), $this->key)
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
        $this->outData = static::unpack($result, $this->key);
    }
}