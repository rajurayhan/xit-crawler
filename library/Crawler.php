<?php 
require './vendor/autoload.php';
use JonnyW\PhantomJs\Client;
class Crawler{

    public static function getPage($url){
        $client = Client::getInstance();

        $request    = $client->getMessageFactory()->createRequest($url, 'GET');        
        $response   = $client->getMessageFactory()->createResponse();        
        $client->send($request, $response);

        return $response;
    }
}