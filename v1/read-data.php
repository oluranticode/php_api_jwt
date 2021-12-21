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

        // to get all haeders
        $all_headers = getallheaders();

        $data->jwt = $all_headers['Authorization'];

        if(!empty($data->jwt)){


            try{
                $secret_key = "odt123";
                $decoded_data = JWT::decode($data->jwt, $secret_key, array('HS512'));
                
                $user_id = $decoded_data->data->id;

                 http_response_code(200);
                 echo json_encode(array(
                     "status"=>1,
                     "message"=> "we have got a jwt token",
                     "user_data" => $decoded_data,
                     "user_id" => $user_id 
                 ));
            } catch(Exception $ex){
                // error message
                http_response_code(500);
                echo json_encode(array(
                    "status" => 0,
                    "message" => $ex->getMessage()
                ));
            }

        }
    }
?>