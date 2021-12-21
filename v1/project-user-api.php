<?php 
ini_set("display_errors", 1); 
require('../vendor/autoload.php');
use \Firebase\JWT\JWT;

// include headers
header("Access-Control-Allow-Origin: *");   //it allow all origin like localhost, any domain and sub domain to access this api
header("Content-type: application/json; charset=UTF-8"); //data which we are getting inside request
header("Access-Control-Allow-Methods: GET"); // method type


    // including files
    include_once("../config/database.php");
    include_once("../classes/Users.php");

    // includes the objects
    $db = new Database();
    $connection = $db->connect();
    $users_obj = new Users($connection);

    if($_SERVER['REQUEST_METHOD'] === "GET"){ 

        $headers = getallheaders();
        $jwt = $headers["Authorization"];

        try{

            $secret_key = "odt123";
            $decoded_data = JWT::decode($jwt, $secret_key, array('HS512')); //decode the jwt token into data 
            $users_obj->user_id = $decoded_data->data->id;

            $projects = $users_obj->get_user_projects();
            // print_r($projects);
            // exit();
            if($projects->num_rows > 0) {
                $project_arr = array();
                while($row = $projects->fetch_assoc()){
                    $project_arr[] = array(
                        "id" => $row["id"],
                        "Project name" => $row["name"],
                        "description" => $row["description"],
                        "status" => $row["status"],
                        "user id" => $row["user_id"],
                        "Date" => $row["created_at"]
                    );
                }

                http_response_code(200); //ok response
                echo json_encode(array(
                    "status" => 1,
                    "Projects" => $project_arr
                ));
            }else {
                http_response_code(404);
                echo json_encode(array(
                    "status" => 0,
                    "message" => "No Project Found"
                ));
            }
        }catch(Exception $ex){
            http_response_code(503);
            echo json_encode(array(
                "status" => 0,
                "message" => $ex->getMessage()
            ));
        }

        
       }
?>