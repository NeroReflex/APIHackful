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

trait EncryptionTrait
{
    protected static $algorithm = 'aes-256-ctr';

    public static function encrypt($plain, $encryptionKey = "") : string
    {
        $encryptionKey = (strlen($encryptionKey) == 0) ? APIHACKFUL_ENCRYPTION_KEY : $encryptionKey;

        $ivLength = openssl_cipher_iv_length(static::$algorithm);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $result = openssl_encrypt(
            $plain,
            static::$algorithm,
            base64_decode($encryptionKey),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );

        return static::$algorithm . '$' . bin2hex($iv) . base64_encode($result);
    }
}