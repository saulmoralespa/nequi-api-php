<?php

use PHPUnit\Framework\TestCase;
use Nequi\Client;

/**.
*  @author Saul Morales Paacheco <info@saulmoralespa.com>
*/
class NequiTest extends TestCase
{

    /**
     * @var
     */
    public $client;

    public function setUp()
    {
        $this->client = new Client(
            getenv("API_KEY"),
            getenv("SECRET_KEY"),
            getenv("ACCESS_KEY"),
            getenv("CLIENT_ID")
        );
    }

    public function testValidateAccount()
    {

        $data = $this->client->validateClient('3195414070', "0");
        $this->assertObjectHasAttribute('ResponseMessage', $data);
    }
}
