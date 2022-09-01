<?php

    require_once("resources/config.php");

    if(isset($_SESSION["user_email"])){
        
        
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $email = $_POST["logEmail"];
        $pass = $_POST["logPassword"];

        $login = new LoginHandler($email, $pass);
        $login->checkUser();
    }

    class Login extends Database{


    }

    class LoginHandler extends Login {

        private $email;
        private $pass;

        public function __construct($email, $pass) {

            $this->email = $email;
            $this->pass = $pass;

        }

        public function checkUser(){

            if(!$this->emailCheck()){
                header("location: index.php?error=invalidEmail");
                
                exit();
            }
            if(!$this->passCheck()){
                header("location: login.php?error=invalidPassword");
                exit();
            }
            $this->getUser($this->email, $this->pass);
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

    // function console_log($output, $with_script_tags = true) {
    //     $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
    // ');';
    //     if ($with_script_tags) {
    //         $js_code = '<script>' . $js_code . '</scrip>';
    //     }
    //     echo $js_code;
    // }
?>