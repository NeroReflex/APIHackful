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
 * The data encoder trait.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DataEncoderTrait
{
    use EncryptionTrait;

    /**
     * Prepare data to be sent to the server.
     *
     * @param array $data the data to be passed to the RESTful server
     * @param string $key the encryption key
     * @return string the *unencoded* data to be passed to the RESTful server
     * @throws \RuntimeException the error preventing data to be encoded
     */
    public static function pack(array $data, $key = "")
    {
        //json encode the given array
        $jsonEncoded = json_encode($data);

        //binary compress the generated json
        $binaryCompressed = gzencode($jsonEncoded, 9);

        if ($binaryCompressed === false) {
            throw new \RuntimeException("Error encoding the given data");
        }

        //return the binary safe representation
        return static::encrypt($binaryCompressed, $key);
    }
}