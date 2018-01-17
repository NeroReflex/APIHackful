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


trait DecryptionTrait
{
    public function decrypt($encrypted, $encryptionKey = "") : string
    {
        $encryptionKey = (strlen($encryptionKey) == 0) ? APIHACKFUL_ENCRYPTION_KEY : $encryptionKey;

        //retrieve the algorithm and the data
        $algo_data = explode('$', $encrypted, 2);
        $algorithm = $algo_data[0];
        $data = $algo_data[1];

        $ivLenghtRepresentation = 2 * openssl_cipher_iv_length($algorithm);
        $iv = hex2bin(substr($data, 0, $ivLenghtRepresentation));

        $encryptedText = substr($data, $ivLenghtRepresentation);

        return openssl_decrypt(
            base64_decode($encryptedText),
            $algorithm,
            base64_decode($encryptionKey),
            OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING,
            $iv
        );
    }
}