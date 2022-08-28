<?php
    //Boilerplate session code, change variables below to allow more user dat to be stored for a session
    if(!isset($_SESSION)){
        session_start();

        //Set session variables if not set
        $_SESSION["logged_in"] = $_SESSION["logged_in"] ?? false;
        $_SESSION["user_role"] = $_SESSION["user_role"] ??  "";
        $_SESSION["user_id"] = $_SESSION["user_id"] ?? "";
        $_SESSION["user_firstname"]= $_SESSION["user_firstname"] ?? "";
        $_SESSION["user_lastname"]= $_SESSION["user_lastname"] ?? "";
        $_SESSION["user_username"] = $_SESSION["user_username"] ?? "";
        $_SESSION["user_email"] = $_SESSION["user_email"] ?? "";

    }


    // if($_SESSION["logged_in"] == false){
    //     header("Location: index.php");
    // }
    


    class Database{
        private static $instance = null;
        private $connection;

        private $servername = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "u21533572"; 

        /**
         * Constructor for the database class
         */
        private function __construct() {
            $this->connection = new mysqli($this->servername, $this->username, $this->password, $this->db);
            if($this->connection->connect_error) {
                die("Connection failed: " . $this->connection->connect_error);
            } 
        }

        /**
         * Create a new database connection if one does not exist
         * @return Instance
         */
        public static function getInstance(){
            if(!self::$instance){
                self::$instance = new Database();
            }

            return self::$instance;
        }

        /**
         *  Destructor for the database class
         */
        public function __destruct() { 
            $this->connection->close();
        }

        /**
         * Returns a connection to the database
         * @return Connection
         */
        public function getConnection(){
            return $this->connection;
        }

        /**
         * Public method to add a user to the database
         * @param $firstname
         * @param $lastname
         * @param $username
         * @param $email
         * @param $password
         * @return bool || inserted id
         */
        public function addUser($firstname, $lastname, $username, $email, $password){
            $stmt = $this->connection->prepare("INSERT INTO dbusers (`firstname`, ,`lastname` `username`, `email`, `password`) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $firstname, $lastname, $username, $email, $password);

            //If statement executed successfully
            if($stmt->execute()){
                return $this->getConnection()->insert_id;
            }
            else{
                return false;
            }
        }

        /**
         * Public method to get user from DB with email and password
         * @param $email - email of user
         * @param $password - password of user
         * @return bool || user object
         */
        public function getUser($email, $password){
            $stmt = $this->connection->prepare("SELECT * FROM dbusers WHERE email=?");
            $stmt->bind_param("s", $email);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    if($row["password"] === $password){
                        return $row;
                    }
                    else{
                        return false;
                    }
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
        }
    }
?>