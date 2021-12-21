    <?php 
        class Users{
            // define all the properties
            public $id;
            public $name;
            public $email;
            public $password;
            public $user_id;
            public $project_name;
            public $description;
            public $status;
            

            private $conn; // database connection
            private $users_tbl; // table name for users
            private $projects_tbl; // table name for projects


            public function __construct($db){
                $this->conn = $db;
                $this->users_tbl = "tbl_users";
                $this->projects_tbl = "tbl_projects";
            }

            
            public function create_user(){
                $user_query = "INSERT INTO $this->users_tbl SET name = ?, email = ?, password = ?";

                // parameter query
                $user_result = $this->conn->prepare($user_query);

                // bind the parameter
                $user_result->bind_param("sss", $this->name, $this->email, $this->password);

                if($user_result->execute()){
                    return true;
                } 
                return false;
            }

            public function check_email(){
                $email_query = "SELECT * FROM $this->users_tbl WHERE email = ?";
                // preapre query
                $email_obj = $this->conn->prepare($email_query);
                // bind the parameters
                $email_obj->bind_param("s", $this->email);
                // execute the query
                if($email_obj->execute()){
                    $data = $email_obj->get_result();
                    return $data->fetch_assoc();
                }
                return array();
            }

            public function check_login(){
                $login_query = "SELECT * FROM $this->users_tbl WHERE email = ?";
                // preapre query
                $login_obj = $this->conn->prepare($login_query);
                // bind the parameters
                $login_obj->bind_param("s", $this->email);
                // execute the query
                if($login_obj->execute()){
                    $data = $login_obj->get_result();
                    return $data->fetch_assoc();
                }
                return array();
            }

            public function check_logins(){
                $email_query = "SELECT * FROM $this->users_tbl WHERE email = ? AND password = ?";
                // preapre query
                $email_obj = $this->conn->prepare($email_query);
                // bind the parameters
                $email_obj->bind_param("ss", $this->email, $this->password);
                // execute the query
                if($email_obj->execute()){
                    $data = $email_obj->get_result();
                    return $data->fetch_assoc();
                }
                return array();
            }

            // create project
            public function project(){
                $project_query = "INSERT INTO $this->projects_tbl SET user_id = ?, name = ?, description = ?, status = ?";
                // prepare query
                $project_obj = $this->conn->prepare($project_query);

                // sanitization..........
                $project_name = htmlspecialchars(strip_tags($this->project_name));
                $description = htmlspecialchars(strip_tags($this->description));
                $status = htmlspecialchars(strip_tags($this->status));
                                
                // bind the paramemter with the prepared query
                $project_obj->bind_param("ssss", $this->user_id, $project_name, $description, $status);

                // execute the query
                if($project_obj->execute()){
                    return true;
                }
                return false;
            }
            public function get_all_project(){
                $project_query = "SELECT * FROM tbl_projects ORDER BY id DESC";
                $project_obj = $this->conn->prepare($project_query);
                // execute the query
                $project_obj->execute();
                return $project_obj->get_result();

            }

            public function get_user_projects(){
                $project_query = "SELECT * FROM tbl_projects WHERE user_id = ? ORDER BY id DESC";
                $project_obj = $this->conn->prepare($project_query);
                // bind the prepare query
                $project_obj->bind_param("i", $this->user_id);
                // execute the query
                $project_obj->execute();
                return $project_obj->get_result();

            }

            public function delete_users(){
                $delete_query = "DELETE FROM $this->users_tbl WHERE id = ?";
                // prepare statement
                $delete_obj = $this->conn->prepare($delete_query);
                // bind the prepare statement
                $delete_obj->bind_param("i", $this->id);

                // execute the query
                if($delete_obj->execute()){
                    return true;
                }
                return false;
            }

            // update users
            public function update_users(){
                $update_query = "UPDATE $this->users_tbl SET name = ?, email = ? WHERE id = ?";
                // prepare statement
                $update_obj = $this->conn->prepare($update_query);
                // sanitize the input
                $this->name = htmlspecialchars(strip_tags($this->name));
                $this->email = htmlspecialchars(strip_tags($this->email));

                // bind the parameters
                $update_obj->bind_param("ssi", $this->name, $this->email, $this->id);

                // execute the query
                if($update_obj->execute()){
                    return true;
                }
                return false;
            }

        }
    ?>