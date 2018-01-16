<?php


namespace APIHackful;

/**
 * The data decoder trait.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DataDecoderTrait
{
    /**
     * Process data received by the server.
     *
     * @param string $data the data to be passed to the RESTful server
     * @return array the *unencoded* data received from the RESTful server
     */
    public static function unpack($data) : array
    {
        //decompress the binary unsafe compressed string
        $jsonEncoded = zlib_decode($data);

        $content = json_decode($jsonEncoded, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \RuntimeException("The given data is not valid json content, json_decode error:" . json_last_error_msg());
        }

        return $content;
    }
}