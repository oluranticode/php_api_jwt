<?php 
ini_set("display_errors", 1); 

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
        $projects = $users_obj->get_all_project();
            //print_r($projects);
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
       }
?>