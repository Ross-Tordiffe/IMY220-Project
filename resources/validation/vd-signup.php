<?php
    
    require_once("../config.php");


    // //===Signup=Controller=Class

    class SignupHandler extends Database {

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
                    header("location: /IMY220/IMY220-Project/index.php?error=Please-enter-a-first-name.");
                    exit();
                }
                else if(empty($this->lastname) || strlen($this->lastname) <= 0){
                    header("location: /IMY220/IMY220-Project/index.php?error=Please-enter-a-last-name.");
                    exit();
                }
                else if(!$patternName){
                    header("location: /IMY220/IMY220-Project/index.php?error=Please-enter-a-valid-name.");
                    exit();
                }
                else if(!$patternSurname){
                    header("location: /IMY220/IMY220-Project/index.php?error=Please-enter-a-valid-surname.");
                    exit();
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

        public function userCheck(){
            if(!$this->nameSurCheck()){
                header("location: /IMY220/IMY220-Project/index.php?error=Name or surname is invalid&signup=true&firstname=".$this->firstname."&lastname=".$this->lastname);
                exit();
            }
            if(!$this->emailCheck()){
                header("location: index.php?error=Email is invalid&signup=true");
                exit();
            }
            if(!$this->passCheck()){
                header("location: index.php?error=Password is invalid&signup=true");    
                exit();
            }
            if(!$this->passConfCheck()){
                header("location: index.php?error=Password confirmation is invalid&signup=true");
                exit();
            }
            if(!$this->DuplicateUserCheck()){
                header("location: index.php?error=That-user-already-exists.-Try-logging-in-instead.&signup=true");
                exit();
            }

            
            $this->addUser($this->email, $this->username, $this->password, $this->firstname, $this->lastname, 'dark');
        }

    }

    // Listen for signin POST requests
    $email = $username = $password = $password_confirm = $firstname = $lastname = $theme = "";
    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $email = $_POST["su-email"];
        $username = $_POST["su-username"];
        $password = $_POST["su-password"];
        $password_confirm = $_POST["su-password-confirm"];
        $firstname = $_POST["su-firstname"];
        $lastname = $_POST["su-lastname"];

        $signup = new SignupHandler($email, $username, $password, $password_confirm, $firstname, $lastname);
        $signup->userCheck();

        header("location: IMY220/IMY220-Project/home.php");
        exit();
    }

?>