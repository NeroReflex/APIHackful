<?php


namespace APIHackful;

/**
 * The data encoder trait.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DataEncoderTrait
{
    /**
     * Prepare data to be sent to the server.
     *
     * @param array $data the data to be passed to the RESTful server
     * @return string the *unencoded* data to be passed to the RESTful server
     */
    public static function pack(array $data)
    {
        //json encode the given array
        $jsonEncoded = json_encode($data);

        //binary compress the generated json
        $binaryCompressed = zlib_encode($jsonEncoded, ZLIB_ENCODING_GZIP, 9);

        //return the binary safe representation
        return $binaryCompressed;
    }
}