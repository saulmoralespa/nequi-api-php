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
        $apikey = "";
        $secretKey = "";
        $access_key = "";
        $clientId = "12345";

        $nequi = new Client($apikey, $secretKey, $access_key, $clientId);

        $data = $nequi->validateClient('3195414070', "0");


        $this->assertObjectHasAttribute('ResponseMessage', $data);
    }
}
