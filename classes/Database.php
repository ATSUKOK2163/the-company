<?php
    class Database{
        private $server_name = "localhost"; //the same as "127.0.0.1" server name
        private $username ="root"; //username
        private $password = "";
        private $db_name = "the_company"; //database name
        protected $conn; //connection object

        public function __construct(){
            #Initialization of the connection variables
            $this->conn = new mysqli($this->server_name,$this->username, $this->password, $this->db_name);

            #Check if there is an errir on the connection object
            #during the runtime
            if($this->conn->connect_error){
                #Display error message if there is an error.
                die("Unable to connect to the database. " . $this->conn->error);
            }
        }
    }
?>