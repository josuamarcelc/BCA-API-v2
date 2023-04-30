<?php
require 'guzzle/vendor/autoload.php';
class BCA_API{
    private static $hostUrl = 'https://sandbox.bca.co.id';
    // private static $hostUrl = 'https://devapi.klikbca.com:8066';
    // private static $hostUrl = 'https://api.klikbca.com:8065';
    private static $clientID = '98ae0b40-d511-4ca5-9de0-xxx';
    private static $clientSecret = '2d211ec8-4270-4e0d-97c0-xxx';
    private static $APIKey = '35a6a8b7-ef6f-4d6d-a076-xxx';
    private static $APISecret = '45e20c7a-e681-434b-bafb-xxx';
    private static $accessToken = null;
    private static $timeStamp = null;
    private static $client;

    public function __construct(){
        self::$timeStamp = date('o-m-d') . 'T' . date('H:i:s') . '.' . substr(date('u'), 0, 3) . date('P');
        self::$client = new \GuzzleHttp\Client;
        $this->initialToken();
    }

    private function initialToken(){
        $output = self::$client->request('POST', self::$hostUrl . '/api/oauth/token', [
            'verify' => false,
            'headers' => [
                 'Content-Type'  => 'application/x-www-form-urlencoded',
                 'Authorization' => 'Basic '.base64_encode(self::$clientID.':'.self::$clientSecret)
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);
        $output = json_decode($output->getBody(), true);
        return self::$accessToken = $output['access_token'];
    }

    private function getSignature($HTTPMethod, $relativeUrl, $RequestBody = ''){
        $RequestBody = strtolower(hash('sha256', $RequestBody));
        $StringToSign = $HTTPMethod . ":" . $relativeUrl . ":" . self::$accessToken . ":" . $RequestBody . ":" . self::$timeStamp;
        $signature = hash_hmac('sha256', $StringToSign, self::$APISecret);
        return $signature;
    }

    public function getStatements($payload = array()){

        $path = '/banking/v2/corporates/'. $payload['corporate_id'] .
                '/accounts/' . $payload['account_number'] .
                '/statements?' .
                'EndDate=' . $payload['end_date'] .
                '&StartDate=' . $payload['start_date'];
        $method = 'GET';

        $output = self::$client->request($method, self::$hostUrl . $path, [
            'verify' => false,
            'headers' => [
                 'Authorization' => 'Bearer ' . self::$accessToken,
                 'Content-Type' => 'application/json',
                 'Origin' => $_SERVER['SERVER_NAME'],
                 'X-BCA-Key' => self::$APIKey,
                 'X-BCA-Timestamp' => self::$timeStamp,
                 'X-BCA-Signature' => $this->getSignature($method, $path),
            ]
        ]);
        // echo '<pre>';
        // print_r(json_decode($output->getBody(), true));
        // echo '</pre>';
        // echo $output->getBody(); // response
        // exit;
        return $output->getBody();
    }

    public function getForex($payload = array()){

        $RateType = (empty($payload['rate_type'])) ? 'E-RATE' : $payload['rate_type'];
        $Currency = (empty($payload['symbol_currency'])) ? 'USD' : $payload['symbol_currency'];

        $path = '/general/rate/forex?Currency=' . $Currency . '&RateType=' . $RateType;
        $method = 'GET';

        $output = self::$client->request($method, self::$hostUrl . $path, [
            'verify' => false,
            'headers' => [
                 'Authorization' => 'Bearer ' . self::$accessToken,
                 'Content-Type' => 'application/json',
                 'Origin' => $_SERVER['SERVER_NAME'],
                 'X-BCA-Key' => self::$APIKey,
                 'X-BCA-Timestamp' => self::$timeStamp,
                 'X-BCA-Signature' => $this->getSignature($method, $path),
            ]
        ]);
        // echo '<pre>';
        // print_r(json_decode($output->getBody(), true));
        // echo '</pre>';
        // echo $output->getBody(); // response
        return $output->getBody();
    }
}


$BCA = new BCA_API();

// $payload = array(
//         'account_number' => 'E-RATE',
//         'symbol_currency' => 'AUD'
//     );
// echo $BCA->getForex($payload);

$payload = array(
        'corporate_id' => 'BCAAPI2016',
        'account_number' => '0201245680',
        'start_date' => '2016-08-29',
        'end_date' => '2016-09-01'
    );
echo '<pre>';
echo $BCA->getStatements($payload);

