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

        $headers = getallheaders();
        

        if(!empty($data->name) && !empty($data->email)){
            $jwt = $headers["Authorization"];
            try{
                $secret_key = "odt123";
                $decoded_data = JWT::decode($jwt, $secret_key, array('HS512')); //decode the jwt token into data 
                $users_obj->id = $decoded_data->data->id;

                $users_obj->name = $data->name;
                $users_obj->email = $data->email;

                if($users_obj->update_users()){
                    http_response_code(200);
                    echo json_encode(array(
                    "status" => 1,
                    "message" => "Updated successfully"
            )); 
                } else{
                    http_response_code(404);
                    echo json_encode(array(
                    "status" => 0,
                    "message" => "Failed to update"
            ));
                }
            }catch(Exception $ex){
                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => $ex->getMessage()
                ));
            }
        } else {
            http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "No Data Found"
                ));
        }
    } else {
        http_response_code(503);
        echo json_encode(array(
            "status" => 0,
            "message" => "Failed to Update"
        ));
    }
?>