<?php

namespace Nequi;

class Client
{
    const HOST = "a7zgalw2j0.execute-api.us-east-1.amazonaws.com";

    const URL_RELATIVE_VALIDATE_CLIENT = "/qa/-services-clientservice-validateclient";

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
     */
    public function validateClient($phoneNumber, $value)
    {

        $body = $this->getBodyValidateClient( $phoneNumber, $value);
        $authorization_header = $this->signed($body, self::URL_RELATIVE_VALIDATE_CLIENT);
        $data = $this->request($body, $authorization_header, self::URL_RELATIVE_VALIDATE_CLIENT);
        return $data;
    }

    /**
     * @param $phoneNumber
     * @param $value
     * @return false|string
     */
    public function getBodyValidateClient($phoneNumber, $value)
    {
        $body = array(
            "RequestMessage"  => array(
                "RequestHeader"  => array (
                    "Channel" => self::CHANNEL,
                    "RequestDate" => gmdate("Y-m-d\TH:i:s\\Z"),
                    "MessageID" => time(),
                    "ClientID" => $this->clientId),
                "RequestBody"  => array (
                    "any" => array (
                        "validateClientRQ" => array (
                            "phoneNumber" => $phoneNumber,
                            "value" => $value
                        )
                    )
                )
            )
        );

        $params = json_encode($body);

        return $params;

    }

    /**
     * @param $body
     * @param $url_relative
     * @param string $method
     * @return string
     */
    public function signed($body, $url_relative, $method='POST')
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
            $url_relative,
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
     * @param $body
     * @param $authorization_header
     * @param $url_relative
     * @param string $method
     * @return mixed
     */
    public function request($body, $authorization_header, $url_relative, $method='POST')
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://".self::HOST.$url_relative);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "Accept: application/json";
        $headers[] = "X-Api-Key: $this->apiKey";
        $headers[] = "X-Amz-Date: " . gmdate( 'Ymd\THis\Z' );
        $headers[] = "Authorization: $authorization_header";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        $data = json_decode($result);

        return $data;

    }

}