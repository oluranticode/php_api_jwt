<?php 
    $ch = curl_init();

     $url = "https://reqres.in/api/users?page=2";
    //$url = "http://localhost/api/v1/list-all.php";

    $fh = fopen("file.tx", "w");

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FILE, $fh);

   curl_exec($ch);

    if($e = curl_error($ch)) {
        echo $e;
    } 

    fclose($fh);
    curl_close($ch);
?>