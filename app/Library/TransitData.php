<?php

namespace App\Library;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TransitData
{
    protected static $instance = null;

    protected static $authKey = null;

    protected static $targetServices = [];

    public function __construct()
    {
        $headerKey = request()->header('Authorization');
        $accessToken = request()->get('access-token');
        $authorization = $headerKey ? $headerKey : $accessToken;

//        if (empty($authorization)) {
//            throw new BadRequestHttpException('Missing access token');
//        }

        self::$authKey = $authorization;
        self::$targetServices = [
            'hrm' => "http://" . config('app.hrm_service_host') . ":8081",
        ];
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($service, $endPoint, $method = "GET", $params = null)
    {
        $destUrl = self::$targetServices[$service] . $endPoint;

        try {
            return [
                'success' => true,
                'error' => 0,
                'data' => $this->makeRequest($destUrl, $method, $params)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 500,
                'message' => $e->getMessage()
            ];
        }
    }

    private function makeRequest($url, $method = "GET", $params = [], $headerOptions = [])
    {
        $headerOptions[] = 'Authorization: ' . self::$authKey;
        $client = new Client();
        $response = $client->request($method, $url, [
            'headers' => ['Authorization' => $headerOptions],
            'query' => $params
        ]);
        return json_decode($response->getBody()->getContents());
    }
}
