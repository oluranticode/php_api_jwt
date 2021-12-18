<?php 
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

    if(!empty($data->name) && !empty($data->email) && !empty($data->password)){ 

        $users_obj->name = $data->name;
        $users_obj->email = $data->email;
        //$users_obj->password = md5($data->password);
        // $users_obj->password = password_hash($data->password, PASSWORD_BCRYPT);
        $users_obj->password = password_hash($data->password, PASSWORD_DEFAULT);

        // call the check email function.......
        $email_data = $users_obj->check_email(); //check if the user email is = to the email in the table
        if(!empty($email_data)){
            // some data we have - insert should not go
            http_response_code(500); //data not found
            echo json_encode(array(
            "status" => 0,
            "message" => "user already exist!"
        ));
        } else {
            if($users_obj->create_user()){
                http_response_code(200); //ok response
                echo json_encode(array(
                "status" => 1,
                "message" => "user has been created"
            ));
            } else {
                http_response_code(500); //data not found
                echo json_encode(array(
                "status" => 0,
                "message" => "Failed to add User"
            ));
            }
        }

    } else {
        http_response_code(500); //All data needed
        echo json_encode(array(
            "status" => 0,
            "message" => "All Data Needed"
        ));
    }

    } else {
        http_response_code(503); //server unavailble
        echo json_encode(array(
            "status" => 0,
            "message" => "Server Unavailable"
        ));
    }
    ?>