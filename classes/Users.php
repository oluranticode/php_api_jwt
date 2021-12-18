    <?php 
        class Users{
            // define all the properties
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
        }
    ?>