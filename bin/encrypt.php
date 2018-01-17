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

include __DIR__ . '../vendor/autoload.php';

use APIHackful\CLI\SecurityImplementation;

global $argv;
global $argc;

if (!defined('APIHACKFUL_ENCRYPTION_KEY')) {
    define('APIHACKFUL_ENCRYPTION_KEY', "8jBsckIGj9XQ7rKIwk53NtbiyP2pH0qJgZKH8cICtr4=");
}

$data = $argv[1];

$encData = SecurityImplementation::encrypt($data);

echo "{$encData}\n";