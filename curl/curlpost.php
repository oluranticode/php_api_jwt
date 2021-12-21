<?php 
 $ch = curl_init();

 $url = "https://reqres.in/api/users";
//$url = "http://localhost/api/v1/list-all.php";

$data_array = array(
    "name"=> "TOPE",
    "job"=> "WEb developer"
);
    $data = http_build_query($data_array);

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// execute curl
$resp = curl_exec($ch);

// check for errors
    if($e = curl_error($ch)){
        echo $e;
    } else {
        $decoded = json_decode($resp);
        foreach($decoded as $key => $val){
            echo $key. " : ". $val. "<br>";
        }
    }

?>