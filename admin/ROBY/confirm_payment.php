<?php
$tillno = $_POST['tillno'];
$amount = $_POST['amount'];
require_once "vendor/autoload.php";
$client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://robi.cemesportal.com/api/Utility/ConfirmTransactions?Amount='.$amount.'&storeNumber='.$tillno, 
            ['verify' => false]);

        // url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $customer = json_decode($response->getBody());
        
        $n = json_encode($customer->resultCode);
        if( $n == '0'){
            echo json_encode(array("error"=>"development"));
        }
        else{
            
        
        echo json_encode($customer);
        
        }

?>
