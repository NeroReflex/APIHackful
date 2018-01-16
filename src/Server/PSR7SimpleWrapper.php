<?php


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