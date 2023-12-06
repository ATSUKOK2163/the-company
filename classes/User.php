<?php
    require_once 'Database.php';

    #The logic of our application will be place here
    class User extends Database{
        public function store($request){
            $first_name = $request['firstname'];
            $last_name= $request['lastname'];
            $username= $request['username'];
            $password= $request['password'];

            #Hashed the Password before inseting into the database
            $password = password_hash($password, PASSWORD_DEFAULT);
            #NOTE: $password - supplied by the user from the form
            #PASSWORD_DEFAULT - The algorithm use tp hashed the password

            #SQL Query string
            $sql = "INSERT INTO users(`first_name`,`last_name`,`username`,`password`) VALUES ('$first_name','$last_name','$username','$password')";

            #Excute the query string
            if($this->conn->query($sql)){
                header("location: ../views"); //index.php or the login page
                exit();                       //same as die()
            }else{
                die("Errir in creating a user. " . $this->conn->error);
            }

        }

        public function login($request){
            $username = $request['username'];
            $password = $request['password'];

            $sql = "SELECT * FROM users WHERE username = '$username'";

            $result = $this->conn->query($sql);

            #check the username if available
            if($result->num_rows == 1){  //if true,meaning that the username is available
                #check if the password supplied is correct
                $user = $result->fetch_assoc();
                #$user['id' =>1, 'first_name' =>'marry' , 'last_name'=>'watson', 'username'=> 'mary', 'password' =>'8*23ka723'・・・・]

                #Check and compare the supplied password against the password alreday in the databases
                if(password_verify($password, $user['password'])){
                    #Create the session variable  --to use later
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['fullname'] = $user['first_name'] . " " .$user['last_name'];

                    header("location: ../views/dashboard.php");  //we will create dashboard.php later on
                    exit;
                }else{
                    die("Password do not matched.");
                }
            }else{
                die("Username not found.");
            }
        }

        public function logout(){
            session_start();
            session_unset();
            session_destroy();

            header("location: ../views");
            exit;
        }

        public function getAllUsers(){
        $sql ="SELECT id, first_name, last_name, username, photo FROM users";

            if($result = $this->conn->query($sql)){
                return $result;
            }else{
                die("Error in retrieving all users." . $this->conn->error);
            }
        }

        public function getUser(){
            $id = $_SESSION['id'];  //get the id of the logged-in user

            $sql = "SELECT first_name, last_name, username, photo FROM users WHERE id = '$id'";
            if($result = $this->conn->query($sql)){
                return $result->fetch_assoc();
            }else{
                die("Error in retriving user: " . $this->conn->error);
            }
        }

        public function update($request,$files){
            session_start();
            $id = $_SESSION['id']; //user who is currently logged-in
            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];

            # Image file
            //these 2 sentenses are required
            $photo =$files['photo']['name'];
            $tmp_photo =$files['photo']['tmp_name'];

            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = '$id'";

            if($this->conn->query($sql)){
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = "$first_name $last_name";

                #Check if there is an uploaded photo, save to Db and save the file into the image folder
                if($photo){
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = '$id'";
                    $destination = "../assets/images/$photo";

                    if ($this->conn->query($sql)) {
                        #Save the omage into Db
                        if(move_uploaded_file($tmp_photo,$destination)){
                            header("location: ../views/dashboard.php");
                            exit;
                        }else{
                            die("Error in updating photo." . $this->conn->error);
                        }
                    }
                }

                header("location: ../views/dashboard.php");
                exit;
            }else{
                die("Error in updating the user." . $this->conn->error);
            }
        }

        #This method is called from the action file
        public function delete(){
            session_start();
            $id =$_SESSION['id'];   //the id of the currently logged-in user

            #Query string
            $sql = "DELETE FROM users WHERE id='$id'";

            #Excute the query string
            if ($this->conn->query($sql)){  //if this is okay, call the logout method
                $this->logout();    //call the logout method
            }else{
                die("Error in deleting your account.");
            }
        }
    }

?>