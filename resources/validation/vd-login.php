<?php

    require_once("resources/config.php");

    $db = Database::getInstance();

    class LoginHandler extends Database {

        private $email;
        private $pass;

        public function __construct($email, $pass) {

            $this->email = $email;
            $this->pass = $pass;

        }

        public function checkUser(){

            if(!$this->emailCheck()){
                return json_encode(array("status" => "error", "message" => "Please enter a valid email."));
            }
            else if(!$this->passCheck()){
                return json_encode(array("status" => "error", "message" => "Password is invalid"));
            }
            else {

                $user = $this->getUser($this->email, $this->pass);
                if(!$user){
                    return json_encode(array("status" => "error", "message" => "User not found"));
                    
                }
                else if($user['user_password'] !== $this->pass){
                    return json_encode(array("status" => "error", "message" => "Password is incorrect"));
                }
                else {
                    $this->setUser($this->email, $this->pass);
                    return json_encode(array("status" => "success", "user_id" => $user['user_id']));
                }
            }

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
            $low = preg_match("@[a-zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž]@", $this->pass);
            $up = preg_match("@[A-ZÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð]@", $this->pass);
            $num = preg_match("@[0-9]@", $this->pass);
            $sym = preg_match("@[!\@#\$%\^&\*]@", $this->pass);
            if(empty($this->pass) || $low == 0 || $up == 0 || $num == 0 || $sym == 0 || strlen($this->pass) < 8){
                return false;
            }
            else {
                return true;
            }
        }
    }
?>