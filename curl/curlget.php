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
        echo '<pre>';
          //print_r($decoded);
        
    $data = $decoded['data'][0]['id'];
    echo $data;

    $image = $decoded['data'][0]['avatar'];
    }

    curl_close($ch);
?>

<img src="<?php echo $image ?>" alt="">
 