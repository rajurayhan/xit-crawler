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

    public static function generateCSV($array){
        // Open a file in write mode ('w')
        $fp = fopen('products.csv', 'w');
        
        // Loop through file pointer and a line
        foreach ($array as $fields) {
            fputcsv($fp, $fields);
        }
        
        fclose($fp);
    }
}