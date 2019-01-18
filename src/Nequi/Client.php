<?php

namespace Nequi;

class Client
{
    const HOST = "a7zgalw2j0.execute-api.us-east-1.amazonaws.com";

    const URL_RELATIVE_VALIDATE_CLIENT = "/qa/-services-clientservice-validateclient";

    const URL_RELATIVE_CASHIN_SERVICE = '/qa/-services-cashinservice-cashin';

    const URL_RELATIVE_CASHOUT_CONSULT = "/qa/-services-cashoutservice-cashoutconsult";

    const URL_RELATIVE_KEY_SERVICE_PUBLIC =  "/qa/-services-keysservice-getpublic";

    const URL_RELATIVE_CASHOUT_SERVICE = "/qa/-services-cashoutservice-cashout";

    const URL_RELATIVE_REVERSE_TRANSACTION = "/qa/-services-reverseservices-reversetransaction";

    const REGION_AWS = "us-east-1";

    const CHANNEL = "MF-001";

    /**
     * @var
     */
    protected $apiKey;

    /**
     * @var
     */
    protected $secretKey;

    /**
     * @var
     */
    protected $accessKey;

    /**
     * @var
     */
    private $clientId;

    /**
     * @var
     */
    protected $_authorizationHeader;

    /**
     * @var
     */
    public $urlRelative;

    /**
     * Client constructor.
     * @param $apiKey
     * @param $secretKey
     * @param $accessKey
     * @param $clientId
     */
    public function __construct($apiKey, $secretKey, $accessKey, $clientId)
    {

        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->accessKey = $accessKey;
        $this->clientId = $clientId;
    }

    /**
     * @param $phoneNumber
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function validateClient($phoneNumber, $value = '0')
    {

        $contentBody = array(
            "any" => array (
                "validateClientRQ" => array (
                    "phoneNumber" => $phoneNumber,
                    "value" => $value
                )
            )
        );

        $body = $this->constructorBody($contentBody);
        $this->setUrlRelative(self::URL_RELATIVE_VALIDATE_CLIENT);
        $authorization_header = $this->signed($body);
        $this->setAuthorizationHeader($authorization_header);
        $data = $this->_exec($body);
        return $data;
    }

    /**
     * @param $phoneNumber
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function cashService($phoneNumber, $value)
    {
        $contentBody = array(
            "any" => array (
                "cashInRQ" => array (
                    "phoneNumber" => $phoneNumber,
                    "code" => "1",
                    "value" => $value
                )
            )
        );

        $destination = array(
            "Destination" => array(
                "ServiceName" => "CashInService",
                "ServiceOperation" => "cashIn",
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            )
        );

        $body = $this->constructorBody($contentBody,$destination);
        $this->setUrlRelative(self::URL_RELATIVE_CASHIN_SERVICE);
        $authorization_header = $this->signed($body);
        $this->setAuthorizationHeader($authorization_header);
        $data = $this->_exec($body);
        return $data;
    }

    /**
     * @param $phoneNumber
     * @return mixed
     * @throws Exception
     */
    public function cashoutConsult($phoneNumber)
    {
        $contentBody = array(
            "any" => array (
                "cashOutConsultRQ" => array (
                    "phoneNumber" => $phoneNumber
                )
            )
        );

        $destination = array(
            "Destination" => array(
                "ServiceName" => "CashOutServices",
                "ServiceOperation" => "cashOutConsult",
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            )
        );

        $body = $this->constructorBody($contentBody,$destination);
        $this->setUrlRelative(self::URL_RELATIVE_CASHOUT_CONSULT);
        $authorization_header = $this->signed($body);
        $this->setAuthorizationHeader($authorization_header);
        $data = $this->_exec($body);
        return $data;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getKeyPublic()
    {
        $this->setUrlRelative(self::URL_RELATIVE_KEY_SERVICE_PUBLIC);
        $authorization_header = $this->signed('{}');
        $this->setAuthorizationHeader($authorization_header);
        $body = "{}";
        $data = $this->_exec($body);
        return $data;
    }

    /**
     * @param $phoneNumber
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function cashoutService($phoneNumber, $value)
    {
        $contentBody = array(
            "any" => array (
                "cashOutRQ" => array (
                    "phoneNumber" => $phoneNumber,
                    "token" => "",
                    "code" => "1",
                    "reference" => " ",
                    "value" => $value
                )
            )
        );

        $destination = array(
            "Destination" => array(
                "ServiceName" => "CashOutServices",
                "ServiceOperation" => "cashOut",
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            )
        );

        $body = $this->constructorBody($contentBody,$destination);
        $this->setUrlRelative(self::URL_RELATIVE_CASHOUT_SERVICE);
        $authorization_header = $this->signed($body);
        $this->setAuthorizationHeader($authorization_header);
        $data = $this->_exec($body);
        return $data;

    }

    /**
     * @param $phoneNumber
     * @param $value
     * @param $messageId
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function reverseTransaction($phoneNumber, $value, $messageId, $type)
    {
        //Detertime type transaction cashin or cashout

        $contentBody = array(
            "any" => array (
                "reversionRQ" => array (
                    "phoneNumber" => $phoneNumber,
                    "value" => $value,
                    "code" => "1",
                    "messageId" => $messageId,
                    "type" => $type
                )
            )
        );

        $destination = array(
            "Destination" => array(
                "ServiceName" => "ReverseServices",
                "ServiceOperation" => "reverseTransaction",
                "ServiceRegion" => "C001",
                "ServiceVersion" => "1.0.0"
            )
        );

        $body = $this->constructorBody($contentBody,$destination);
        $this->setUrlRelative(self::URL_RELATIVE_REVERSE_TRANSACTION);
        $authorization_header = $this->signed($body);
        $this->setAuthorizationHeader($authorization_header);
        $data = $this->_exec($body);
        return $data;
    }

    public function constructorBody($requestBody, $destination = array())
    {
        $headermain =  array(
            "Channel" => self::CHANNEL,
            "RequestDate" => gmdate("Y-m-d\TH:i:s\\Z"),
            "MessageID" => time(),
            "ClientID" => $this->clientId
        );

        $header = array(
            "RequestHeader"  =>
                empty($destination) ? $headermain : array_merge($headermain, $destination)

        );

        $bodyRequest = array(
            "RequestBody"  =>
                $requestBody

        );

        $body = array(
            "RequestMessage"  =>
                array_merge($header, $bodyRequest)
        );

        $params = json_encode($body);

        return $params;

    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrlRelative($url)
    {
        $this->urlRelative = $url;
        return $this;
    }

    private  function setAuthorizationHeader($header)
    {
        $this->_authorizationHeader = $header;
        return $this;
    }

    /**
     * @param $body
     * @param $url_relative
     * @param string $method
     * @return string
     */
    public function signed($body, $method='POST')
    {

        $service = 'execute-api';
        $algorithm = 'AWS4-HMAC-SHA256';
        $alg = 'sha256';

        $amzdate = gmdate( 'Ymd\THis\Z' );
        $amzdate2 = gmdate( 'Ymd' );


        $host = self::HOST;

        $hashedPayload = hash($alg, $body,false);

        $signed_headers = "content-type;host;x-api-key";


        $canonical_headers = array(
            "content-type:application/json",
            "host:$host",
            "x-api-key:$this->apiKey"
        );

        $canonical_headers = $this->prepareDate($canonical_headers, "\n", true, true);

        $canonical_request = array(
            $method,
            $this->urlRelative,
            "",
            $canonical_headers,
            $signed_headers,
            $hashedPayload
        );


        $canonical_request = $this->prepareDate($canonical_request, "\n", true);

        $credential_scope = $amzdate2.'/'.self::REGION_AWS.'/'.$service.'/'.'aws4_request';

        $string_to_sign = array(
            $algorithm,
            $amzdate,
            $credential_scope,
            hash('sha256', $canonical_request)
        );

        $string_to_sign = $this->prepareDate($string_to_sign, "\n", true);

        $kSecret = 'AWS4'.$this->secretKey;
        $kDate = hash_hmac( $alg, $amzdate2, $kSecret, true );
        $kRegion = hash_hmac( $alg, self::REGION_AWS, $kDate, true );
        $kService = hash_hmac( $alg, $service, $kRegion, true );
        $kSigning = hash_hmac( $alg, 'aws4_request', $kService, true );
        $signature = hash_hmac( $alg, $string_to_sign,$kSigning);

        $header_signature = array(
            'Credential' => $this->accessKey . '/' . $credential_scope,
            'SignedHeaders' => $signed_headers,
            'Signature' => $signature
        );

        $header_signature = $this->prepareDate($header_signature, ', ');

        return "$algorithm $header_signature";

    }

    /**
     * @param $data
     * @param $separate
     * @param bool $simple
     * @param bool $end
     * @return string
     */
    public function prepareDate($data, $separate, $simple = false, $end = false)
    {
        if ($simple){
            $paramsJoined = array();
            foreach($data as $param => $value) {
                $paramsJoined[] = $end ? $value . $separate : $value;
            }
            $params = implode($end ? '' : $separate, $paramsJoined);
        }else{
            $get_params  = http_build_query($data, '', $separate);
            $params = urldecode($get_params);
        }

        return $params;
    }

    /**
     * @param $request
     * @throws Exception
     */
    private function _exec($request)
    {
        $connect = $this->_buildRequest($request);

        $api_result = curl_exec($connect);

        $api_http_code = curl_getinfo($connect, CURLINFO_HTTP_CODE);


        if ($api_result === false) {
            throw new  Exception(curl_error($connect));
        }

        $response = json_decode($api_result);

        if ($api_http_code !== 200){
            if (isset($response->message)){
                throw new  Exception($response->message, $api_http_code);
            }
        }

        if (isset($response->ResponseMessage->ResponseHeader->Status->StatusCode)){
            $status = $response->ResponseMessage->ResponseHeader->Status;
            $statusCode = $status->StatusCode;
            if (in_array($statusCode, $this->getCodesErrors())){
                throw new  Exception($status->StatusDesc);
            }
        }

        curl_close($connect);

        return $response;
    }

    /**
     * @param $body
     * @param $authorization_header
     * @param $url_relative
     * @param string $method
     * @return mixed
     */
    private function _buildRequest($body, $method='POST')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://".self::HOST.$this->urlRelative);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json";
        $headers[] = "X-Api-Key: $this->apiKey";
        $headers[] = "X-Amz-Date: " . gmdate( 'Ymd\THis\Z' );
        $headers[] = "Authorization: $this->_authorizationHeader";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $ch;
    }

    public function getCodesErrors()
    {
        return array(
            '2-CCSB000012',
            '2-CCSB000013',
            '2-CCSB000079',
            '3-451',
            '3-455',
            '10-454',
            '10-455',
            '11-9L',
            '11-17L',
            '11-18L',
            '11-37L',
            '20-05A',
            '20-07A',
            '20-08A'
        );
    }

}