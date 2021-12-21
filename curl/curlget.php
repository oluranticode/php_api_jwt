<?php 
//   echo $_SERVER['REMOTE_ADDR']; //get client ip address
    $ch = curl_init();

     $url = "https://reqres.in/api/users?page=2";
    //$url = "http://localhost/api/v1/list-all.php";

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);// get the response bck from the other server

    $resp = curl_exec($ch); //executing the curl command

    if($e = curl_error($ch)) {
        echo $e;
    } else {
        $decoded = json_decode($resp, true);
          print_r($decoded);
        // $encoded =json_encode($decoded);
        // echo $encoded[data];
        echo $decoded['data'];
     
    }

    curl_close($ch);
?>