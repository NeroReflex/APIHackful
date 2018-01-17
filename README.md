# APIHackful
An implementation of RESTful API with end-to-end encryption
and compression, with a support to PSR-7 standard.

## Usage
In order to implement the library you have to obtain
an encryption key.

Just run the following command on a Linux PC or server
running from more than half an hour:

```bash
head -c 32 /dev/urandom | base64
```

than write in your application startup file:

```php
define('APIHACKFUL_ENCRYPTION_KEY', "8jBsckIGj9XQ7rKIwk53NtbiyP2pH0qJgZKH8cICtr4=");
```

obviously... DO __NOT__ USE THE EXAMPLE!

## WIP
Not ready (yet).