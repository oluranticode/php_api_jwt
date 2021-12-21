<?php 
$city = 'London';
$api_keys = '0ffa5cb1f1bdbde704c88b6337840dc4';
    $api_url = 'http://api.openweathermap.org/data/2.5/weather?q='.$city.'&appid='.$api_keys;

     $weather_api = file_get_contents($api_url);
    //$weather_api = json_decode(file_get_contents($api_url), true);

    $temperature = $weather_api['main']['temp'];
    $cloud = $weather_api['weather'][0]['main'];
    echo $temperature;
    echo $cloud;

    //  echo '<pre>';
    // print_r($weather_api);
?>
