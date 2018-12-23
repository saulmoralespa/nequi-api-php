<?php

use PHPUnit\Framework\TestCase;
use Nequi\Client;

/**.
*  @author Saul Morales Paacheco <info@saulmoralespa.com>
*/
class NequiTest extends TestCase
{
    public function testValidateAccount()
    {
        $apikey = getenv("API_KEY");
        $secretKey = getenv("SECRET_KEY");
        $access_key = getenv("ACCESS_KEY");
        $clientId = getenv("CLIENT_ID");

        $nequi = new Client($apikey, $secretKey, $access_key, $clientId);

        $data = $nequi->validateClient('3195414070', "0");


        $this->assertObjectHasAttribute('ResponseMessage', $data);
    }
}
