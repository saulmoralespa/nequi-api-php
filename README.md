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

//first set the keys and client id of the Nequi API in the .env environment file

$apikey = getenv("API_KEY");
$secretKey = getenv("SECRET_KEY");
$access_key = getenv("ACCESS_KEY");
$clientId = getenv("CLIENT_ID");

// instantiate the Nequi client
$nequi = new Client($apikey, $secretKey, $access_key, $clientId);
```

### Validate account

```php
$phoneNumber="3195414070";
$value="0";
$data = $nequi->validateClient($phoneNumber, $value);
```

### Cash service cashin

```php
$phoneNumber="3195414070";
$value="2000";
$data = $nequi->cashService($phoneNumber, $value);
```

### Cashout consult

```php
$phoneNumber="3195414070";
$data = $nequi->cashoutConsult($phoneNumber);
```

### Get public key

```php
$data = $nequi->getKeyPublic();
```

### Cash service cashout

```php
$phoneNumber="3195414070";
$value="2000";
$data = $nequi->cashoutService($phoneNumber, $value);
```

### Reverse transaction

```php
$phoneNumber="3195414070";
$value="2000";
$messageId="1234567890";
$type="cashin"; //cashin or cashout
$data = $nequi->reverseTransaction($phoneNumber, $value, $messageId, $type);
```

### Reverse transaction

```php
$phoneNumber="3195414070";
$value="2000";
$messageId="1234567890";
$type="cashin"; //cashin or cashout
$data = $nequi->reverseTransaction($phoneNumber, $value, $messageId, $type);
```