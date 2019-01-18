<?php

if (!function_exists('curl_init')) {
    throw new Exception('Nequi needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('Nequi needs the JSON PHP extension.');
}

require_once dirname(__FILE__).'/Nequi/Client.php';
require_once dirname(__FILE__).'/Nequi/Exception.php';