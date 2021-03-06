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
        if(!empty($data->name) && !empty($data->description) && !empty($data->status)){

            try{
                $jwt = $headers['Authorization'];

                $secret_key = "odt123";
                $decoded_data = JWT::decode($jwt, $secret_key, array('HS512')); //decode the jwt token into data 

                $users_obj->user_id = $decoded_data->data->id;
                $users_obj->project_name = $data->name;
                $users_obj->description = $data->description;
                $users_obj->status = $data->status;

                if($users_obj->project()){
                    http_response_code(200);
                    echo json_encode(array(
                    "status" => 1,
                    "message" => "Project has been created"
            )); 
                } else{
                    http_response_code(404);
                    echo json_encode(array(
                    "status" => 0,
                    "message" => "Failed to create projects"
            ));
                }

            }catch(Exception $ex){
                http_response_code(500); //server error
                echo json_encode(array(
                    "status" => 0,
                    "message" => $ex->getMessage()
                ));
            }

        }else{
            http_response_code(404);
            echo json_encode(array(
                "status" => 0,
                "message" => "All data needed"
            ));
        }
    }
?>