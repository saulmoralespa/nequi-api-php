Nequi client API PHP
============================================================

## Installation

Use composer package manager

```bash
composer require saulmoralespa/nequi-api-php
```

#### Bootstrapping autoloader and instantiating a client

```php
// ... please, add composer autoloader first
include_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// import client class
use Nequi\Client;

// instantiate the Linkedin client
$nequi = new Client($apikey, $secretKey, $access_key, $clientId);
$data = $nequi->validateClient('3195414070', "0");
```