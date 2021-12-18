<?php 
require('../vendor/autoload.php');
use \Firebase\JWT\JWT;

    ini_set("display_errors", 1); 
// include headers
header("Access-Control-Allow-Origin: *"); //it allow all origin like localhost, any domain and sub domain to access this api
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

        if(!empty($data->email) && !empty($data->password)){
            $users_obj->email = $data->email;
            $users_obj->password = password_hash($data->password, PASSWORD_DEFAULT) ;

            $user_data = $users_obj->check_logins();
            if(!empty($user_data)){
                $name = $user_data['name'];
                $email = $user_data['email'];
                $password = password_hash($user_data['password'], PASSWORD_DEFAULT);

                if(password_verify($data->password, $password)){
                    http_response_code(200);
            echo json_encode(array(
                "status" => 1,
                "message" => "uers Logged in successfully"
            ));
                } else {
                    http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "Invalid credentials"
            ));
                }
            }else {
                http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "Invalid details"
            ));
            }

        }else {
            http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "All data needed"
            ));
        }
    }