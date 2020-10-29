<?php

namespace Artifacts\Baseball\Helper;

class Helper
{

    public static function GetApi($url) {
        $client = new \GuzzleHttp\Client();
        $request = $client->get($url);
        $response = $request->getBody();
        return $response;
    }


    public static function PostApi($url,$body) {
        $client = new \GuzzleHttp\Client();
        $response = $client->createRequest("POST", $url, ['body'=>$body]);
        $response = $client->send($response);
        return $response;
    }
}