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

    if($_SERVER['REQUEST_METHOD'] === "POST"){ // 1..
        $data = json_decode(file_get_contents("php://input"));
        if(!empty($data->email) && !empty($data->password)){ // 2..
            
            $users_obj->email = $data->email;
            $users_obj->password =  $data->password;

            $user_data = $users_obj->check_login(); //check if user details are the same with the one in the table db
            if(!empty($user_data)){ // 3..
                $name = $user_data['name'];
                $email = $user_data['email'];
                $password = $user_data['password'];

                if(!password_verify($users_obj->password, $password)){ // 4.. //compare the normal password to th db password
               
                    // jwt
                    $iss = "localhost";
                    $iat = time();
                    $nbf = $iat + 10;
                    $exp = $iat +30;
                    $aud = "myusers";
                    $user_arr_data = array(
                        "id" => $user_data['id'],
                        "name" => $user_data['name'],
                        "email" => $user_data['email']
                    );

                    $secret_key = "odt123";

                    $payload_info = array(
                        "iss" => $iss, // issue word, what this payload means token
                        "iat" => $iat, //at what time
                        "nbf" => $nbf, //time interval
                        "exp" => $exp, //expiring date
                        "aud" => $aud,
                        "data" => $user_arr_data
                    );
                   $jwt = JWT::encode($payload_info, $secret_key, 'HS512');

                   http_response_code(200); //ok response
                    echo json_encode(array(
                        "status" => 1,
                        "jwt" => $jwt,
                        "message" => "User logged in successfully"
                    ));
                } else{ // 4..
                    http_response_code(200); //ok response
                    echo json_encode(array(
                        "status" => 0,
                        "message" => "User logged in successfully"
                    ));
                }
            } else { // 3..
                http_response_code(404); //data not found
            echo json_encode(array(
                "status" => 0,
                "message" => "User does not exist"
            ));
            }
        } else{ // 2.. 
            http_response_code(404); //data not found
            echo json_encode(array(
                "status" => 0,
                "message" => "User does not exist"
            ));
        }
    } else{  // 1.. 
        http_response_code(503); //server unavailble
        echo json_encode(array(
            "status" => 0,
            "message" => "Server Unavailable"
        ));
    }
?>