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

namespace APIHackful\Server;

use APIHackful\DataDecoderTrait;
use APIHackful\DataEncoderTrait;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * The wrapper around PSR-7 Messaging.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
class PSR7SimpleWrapper
{
    use DataEncoderTrait;
    use DataDecoderTrait;

    protected function readContent(RequestInterface &$request) : array
    {
        //rewind the body stream
        $request->getBody()->rewind();

        //get the body content
        $requestContent = (string)$request->getBody()->getContents();

        return static::unpack($requestContent);
    }

    protected function writeContent(ResponseInterface &$response, array &$data)
    {
        //write the encoded content
        $response->getBody()->write(static::pack($data));
    }
}