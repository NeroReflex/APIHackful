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
 * The encryption algorithm.
 *
 * @author Denis Benato <beanto.denis96@gmail.com>
 */
trait EncryptionTrait
{
    protected static $algorithm = 'aes-256-ctr';

    /**
     * Encrypt a message that will be decrypted using the library
     * decryption function.
     *
     * @param string $plain the plain text message
     * @param string $encryptionKey the base64 encryption key
     * @return string the encrypted message
     */
    public static function encrypt($plain, $encryptionKey = "") : string
    {
        if (!is_string($plain)) {
            throw new \InvalidArgumentException("The plain message is not a valid string");
        } else if (!is_string($encryptionKey)) {
            throw new \InvalidArgumentException("The encryption key must be given as a string");
        } else if (!in_array(static::$algorithm, openssl_get_cipher_methods(true))) {
            throw new \InvalidArgumentException("The encryption algorithm is not supported");
        }

        $encryptionKey = (strlen($encryptionKey) == 0) ? APIHACKFUL_ENCRYPTION_KEY : $encryptionKey;

        //query for the IV length and generate the correct number of random bytes
        $ivLength = openssl_cipher_iv_length(static::$algorithm);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $result = openssl_encrypt(
            $plain,
            static::$algorithm,
            base64_decode($encryptionKey),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );

        if ($result === false) {
            throw new \RuntimeException('Encryption error');
        }

        return static::$algorithm . '$' . bin2hex($iv) . base64_encode($result);
    }
}