<?php

    //Display all error messages
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('error_reporting', E_ALL);
    error_reporting(E_ALL);

    //Boilerplate session code, change variables below to allow more user dat to be stored for a session
    if(!isset($_SESSION)){
        session_start();

        //Constant session varaiables
        $_SESSION["IVECTOR"] = "cRfTjWnZr4u7x!A%";

        //Set session variables if not set
        $_SESSION["logged_in"] = $_SESSION["logged_in"] ?? false;
        $_SESSION["user_role"] = $_SESSION["user_role"] ??  "";
        $_SESSION["user_id"] = $_SESSION["user_id"] ?? "";
        $_SESSION["user_firstname"]= $_SESSION["user_firstname"] ?? "";
        $_SESSION["user_lastname"]= $_SESSION["user_lastname"] ?? "";
        $_SESSION["user_username"] = $_SESSION["user_username"] ?? "";
        $_SESSION["user_email"] = $_SESSION["user_email"] ?? "";


    }
    
    class Database{
        private static $instance = null;
        private $connection;

        private $servername = "localhost";
        private $username = "u21533572";
        private $password = "(your password)";
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
            $this->connection = null;
            // session_destroy();
        }
        /**
         * Returns a connection to the database
         * @return Connection
         */

        public function getConnection(){
            return $this->getInstance()->connection;
        }

        /**
         * Gets users from the database based on parameters and returns user entry if found, or false if not
         * @param $email - email of user
         * @param $password - password of user
         * @return bool || user object
         */
        protected function getUser($email, $password){
            
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_email=?");
            $stmt->bind_param("s", $email);
            if($stmt->execute()){
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    if($row["user_password"] === $password){
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
            // $stmt->close();
        }

        /**
         * Method to set the user session variables
         * @param $email
         * @param $password
         * @return bool login the user or throw an error
         */
        protected function setUser($email, $password){
            $user = $this->getUser($email, $password);
            if($user == false) {
                header("Location: index.php/?error=There-was-an-error-logging-in");
                return false;
            }
            else {
                $_SESSION["logged_in"] = true;
                $_SESSION["user_role"] = $user["user_role"];
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["user_email"] = $user["user_email"];
                $_SESSION["user_firstname"] = $user["user_firstname"];
                $_SESSION["user_lastname"] = $user["user_lastname"];
                $_SESSION["user_username"] = $user["user_username"];
                $_SESSION["user_theme"] = $user["user_theme"];
              
                // header("Location: home.php/");
                return true;
            }   
            
        }

        protected function duplicateCheck($email){
            $duplicate = $this->getConnection()->prepare("SELECT * FROM `db_users` WHERE `user_email` = ?");
            $duplicate->bind_param("s", $email);
            if(!$duplicate->execute()){
                // $duplicate = null;

                header("location: index.php?error=There-was-an-error-looking-for-duplicate-users.");
                exit();
            }
            $result = $duplicate->get_result();
            
            if($result->num_rows > 0){
                $duplicate_result = false;
            }
            else
                $duplicate_result = true;

            // $duplicate->close();
            return $duplicate_result;
    }

        /**
         * Public method to add a user to the database
         * @param $email
         * @param $username
         * @param $password
         * @param $salt
         * @param $firstname
         * @param $lastname
         * @param $theme
         * @return bool || inserted id
        */
        // protected function addUser($email, $username, $password, $firstname, $lastname, $theme){

        //     //Prepare to add user to database
        //     $stmt = $this->getInstance()->prepare("INSERT INTO db_users (`user_email`, `user_username`, `user_password`, `user_salt` `user_firstname`, `user_lastname`, `user_theme`) VALUES (?, ?, ?, ?, ?, ?, ?)");

        //     //Create a hashed password for the user
        //     $salt = bin2hex(random_bytes(16));
        //     $hashedPass = $this->generatePass($password, $salt);

        //     $stmt->bind_param("sssssss", $email, $username, $hashedPass, $salt, $firstname, $lastname, $theme);
        //     //If statement executed successfully
        //     if($stmt->execute()){
        //         $this->setUser($email, $password);
        //         return $this->getConnection()->insert_id;
        //     }
        //     else{
        //         return false;
        //     }
        // }
       

        /**
         * Method to insert a new event into the database
         * @param $user_id
         * @param $title
         * @param $stime
         * @param $etime
         * @param $sdate
         * @param $edate
         * @param $location
         * @param $img
         * @param $user_count
         * @param $description
         * @param $category_id
         */
    }

    $db = Database::getInstance(); 
?>