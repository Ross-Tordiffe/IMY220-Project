<?php
    
    require_once("resources/config.php");


    //===Signup=Controller=Class

    class Signup extends Database {

        protected function addUser($email, $username, $password, $firstname, $lastname, $theme) {
            //Prepare to add user to database
            $stmt = $this->getInstance()->getConnection()->prepare("INSERT INTO db_users (`user_email`, `user_username`, `user_password`, `user_salt`, `user_firstname`, `user_lastname`, `user_theme`) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
            //Create a hashed password for the user
            $salt = bin2hex(random_bytes(16));
            $hashedPass = $this->generatePass($password, $salt);
    
            $stmt->bind_param("sssssss", $email, $username, $hashedPass, $salt, $firstname, $lastname , $theme);
            //If statement executed successfully
            if($stmt->execute()){
                $this->setUser($email, $hashedPass);
                return $this->getConnection()->insert_id;
            }
            else {
                return false;
            }
        }

    }

    class SignupHandler extends Signup {

        private $email;
        private $username;
        private $password;
        private $password_confirm;
        private $firstname;
        private $lastname;

        public function __construct($email, $username, $password, $password_confirm, $firstname, $lastname) {
            $this->email = $email;
            $this->username = $username;
            $this->password = $password;
            $this->password_confirm = $password_confirm;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
        }

        private function nameSurCheck(){
            $patternName = preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'\- ]*$/", $this->firstname);
            $patternSurname = preg_match("/^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'\- ]*$/", $this->lastname);
            if(empty($this->firstname) || empty($this->lastname) || strlen($this->firstname) <= 0 || strlen($this->lastname) <= 0 || !$patternName || !$patternSurname){
                if(empty($this->firstname) || strlen($this->firstname) <= 0){
                    return false;
                }
                else if(empty($this->lastname) || strlen($this->lastname) <= 0){
                    return false;
                }
                else if(!$patternName){
                    return false;
                }
                else if(!$patternSurname){
                    return false;
                }
            }
            return true;
        }

        private function emailCheck(){
            if(empty($this->email) || !(filter_var($this->email, FILTER_VALIDATE_EMAIL))){
                return false;
            }
            else {
                return true;
            }
        }

        private function passCheck(){
            $low = preg_match("/[a-zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž]/", $this->password);
            $up = preg_match("/[A-ZÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð]/", $this->password);
            $num = preg_match("/[0-9]/", $this->password);
            $sym = preg_match("/[!\@#\$%\^&\*]/", $this->password);
            if(empty($this->password) || $low == 0 || $up == 0 || $num == 0 || $sym == 0 || strlen($this->password) < 8){
                return false;
            }
            else {
                return true;
            }
        }

        private function passConfCheck(){
            if(empty($this->password) || $this->password !== $this->password_confirm){
                return false;
            }
            else {
                return true;
            }
        }

        private function DuplicateUserCheck(){
            if(!$this->duplicateCheck($this->email)){
                return false;
            }
            else {
                return true;
            }
        }

        public function checkUser(){
            if(!$this->nameSurCheck()){
                return json_encode(array("status" => "error", "message" => "Name or surname is not valid"));
            }
            else if(!$this->emailCheck()){
                return json_encode(array("status" => "error", "message" => "Please enter a valid email."));
            }
            else if(!$this->passCheck()){
               return json_encode(array("status" => "error", "message" => "Password is invalid"));
            }
            else if(!$this->passConfCheck()){
                return json_encode(array("status" => "error", "message" => "Passwords do not match"));
            }
            else if(!$this->DuplicateUserCheck()){
                return json_encode(array("status" => "error", "message" => "Email is already in use"));
            }
            else {
                
                $result = $this->addUser($this->email, $this->username, $this->password, $this->firstname, $this->lastname, "dark");
                if(!$result){
                    return json_encode(array("status" => "error", "message" => "User could not be added"));
                    
                }
                else {
                    return json_encode(array("status" => "success", "user_id" => $result));
                }
            }
        }

    }

    // Listen for signin POST requests
    // $email = $username = $password = $password_confirm = $firstname = $lastname = $theme = "";
    // if($_SERVER["REQUEST_METHOD"] === "POST") {

    //     $email = $_POST["su-email"];
    //     $username = $_POST["su-username"];
    //     $password = $_POST["su-password"];
    //     $password_confirm = $_POST["su-password-confirm"];
    //     $firstname = $_POST["su-firstname"];
    //     $lastname = $_POST["su-lastname"];

    //     $signup = new SignupHandler($email, $username, $password, $password_confirm, $firstname, $lastname);
    //     $signup->userCheck();

    //     header("location: IMY220/IMY220-Project/home.php");
    //     exit();
    // }

?>