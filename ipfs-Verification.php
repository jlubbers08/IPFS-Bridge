<?php
global $wopdb;


function randomstring($len)
{
    $string = "";
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    for($i=0;$i<$len;$i++)
        $string.=substr($chars,rand(0,strlen($chars)),1);
    return $string;
}

$requestType = $_SERVER['REQUEST_METHOD'];

if($requestType == "POST"){

    $string = randomstring(50);
    $string = "RHU0g3ux4pjicDG6LMhtUCiM53IopRw9HR1GAoAqNiHQoDc0";
    $requestType = $_SERVER['REQUEST_METHOD'];

    $jsondata = $entityBody = file_get_contents('php://input');
    //logEvent($jsondata);
    $params = json_decode($jsondata, true);


    $code = $params['valid'];
    $response = $arr = array();
    $response = $params;

    update_option('ipfs_trial', $code);

}


//function ActivateTrial(){
//
//
//
//    $jsondata = $entityBody = file_get_contents('php://input');
//    //logEvent($jsondata);
//    $params = json_decode($jsondata, true);
//
//
//    $code = $params['valid'];
//    $response = $arr = array();
//    $response = $params;
//
//    update_option('ipfs_trial', $code);
//
//}