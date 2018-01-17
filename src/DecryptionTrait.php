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
 * The decryption algorithm.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait DecryptionTrait
{
    /**
     * Decrypt a message that was encrypted using the library
     * encryption function.
     *
     * @param string $encrypted the encrypted message
     * @param string $encryptionKey the base64 encryption key
     * @return string the plain text message
     */
    public static function decrypt($encrypted, $encryptionKey = "") : string
    {
        if ((!is_string($encrypted)) || (strlen($encrypted) < 44)) {
            throw new \InvalidArgumentException("The encrypted message is not valid");
        } else if (!is_string($encryptionKey)) {
            throw new \InvalidArgumentException("The encryption key must be given as a string");
        }

        $encryptionKey = (strlen($encryptionKey) == 0) ? APIHACKFUL_ENCRYPTION_KEY : $encryptionKey;

        //retrieve the algorithm and the data
        $algo_data = explode('$', $encrypted, 2);
        $algorithm = $algo_data[0];
        $data = (count($algo_data) == 2) ? $algo_data[1] : "";

        if (!in_array($algorithm, openssl_get_cipher_methods(true))) {
            throw new \InvalidArgumentException("The encryption algorithm is not supported");
        } else if (strlen($data) == 0) {
            throw new \InvalidArgumentException("The encrypted message is malformed");
        }

        //query the length of the IV and extract the IV from the message
        $ivLenghtRepresentation = 2 * openssl_cipher_iv_length($algorithm);
        $iv = hex2bin(substr($data, 0, $ivLenghtRepresentation));

        $encryptedText = substr($data, $ivLenghtRepresentation);

        $plain = openssl_decrypt(
            base64_decode($encryptedText),
            $algorithm,
            base64_decode($encryptionKey),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );

        if ($plain === false) {
            throw new \RuntimeException('Decryption error');
        }

        return $plain;
    }
}