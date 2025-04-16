<?php

$idno = $_POST['id'];
require_once "vendor/autoload.php";
$client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://robi.cemesportal.com/api/Utility/ApproveTransaction?TransID='.$idno, 
            ['verify' => false]);

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $customer = json_decode($response->getBody());
        
         echo json_encode(array("verify"=>"verified"));
        
       

?>
