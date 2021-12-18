<?php 
ini_set("display_errors", 1); 
require('../vendor/autoload.php');
use \Firebase\JWT\JWT;

// include headers
header("Access-Control-Allow-Origin: *");   //it allow all origin like localhost, any domain and sub domain to access this api
header("Content-type: application/json; charset=UTF-8"); //data which we are getting inside request
header("Access-Control-Allow-Methods: POST"); // method type


    // including files
    include_once("../config/database.php");
    include_once("../classes/Users.php");

    // includes the objects
    $db = new Database();
    $connection = $db->connect();
    $users_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === "POST"){ 
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->jwt)){
            http_response_code(200);
            echo json_encode(array(
                "status"=>1,
                "message"=> "we have got a jwt token"
            ));
        }
    }
?>