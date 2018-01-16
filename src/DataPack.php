<?php
/* Copyright © Benato Denis, 2018
 * All rights reserved - Tutti i diritti riservati.
 */

namespace APIHackful;

/**
 * The MLMCalc communication helper.
 *
 * Used to pack and unpack data from/to MLMcalc.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DataPackerTrait
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

        return json_decode($jsonEncoded, true);
    }
}