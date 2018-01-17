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

namespace APIHackful;

/**
 * The data decoder trait.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DataDecoderTrait
{
    use DecryptionTrait;

    /**
     * Process data received by the server.
     *
     * @param string $data the data to be passed to the RESTful server
     * @param string $key the encryption key
     * @return array the *unencoded* data received from the RESTful server
     */
    public static function unpack($data, $key = "") : array
    {
        //decompress the binary unsafe compressed string
        $jsonEncoded = gzdecode(static::decrypt($data, $key));

        if ($jsonEncoded === false) {
            throw new \RuntimeException("Error decoding the given data");
        }

        $content = json_decode($jsonEncoded, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            throw new \RuntimeException("The given data is not valid json content, json_decode error:" . json_last_error_msg());
        }

        return $content;
    }
}