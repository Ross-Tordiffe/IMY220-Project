<?php

    require_once("../default/config.php");

    if(isset($_SESSION["user_email"])){
        
        
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {

        $email = $_POST["logEmail"];
        $pass = $_POST["logPassword"];

        $login = new LoginHandler($email, $pass);
        $login->checkUser();
    }

    class Login extends Database{

        // protected function getUserEntry($email, $pass){
            
        //         // $url = "localhost/u21533572/api.php";
        //         $url = "https://wheatley.cs.up.ac.za/u21533572/api.php";
        //         $data = json_encode(array("type"=>"login", "email"=>$email, "password"=>$pass));

        //         $request = curl_init();
        //         curl_setopt($request, CURLOPT_URL, $url);
        //         curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        //         curl_setopt($request, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        //         curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        //         curl_setopt($request, CURLOPT_USERPWD, "u21533572:Un5t4b13Un1v3r5317?");
        //         $response = curl_exec($request); 

        //         $response = json_decode($response, true);

        //         if($response["status"] === "success"){
        //             $_SESSION["userKey"] = $response["data"]["key"];
        //             $_SESSION["userEmail"] = $response["data"]["email"];
        //             $_SESSION["userName"] = $response["data"]["name"];
        //             $_SESSION["userPref"] = $response["data"]["pref"];
        //             $_SESSION["userTheme"] = $response["data"]["theme"];

        //             setcookie("api_key", $response["data"]["key"], time() + (86400 * 30), '/');
        //             setcookie("theme", $response["data"]["theme"], time() + (86400 * 30), '/');
        //             setcookie("pref", $response["data"]["pref"], time() + (86400 * 30), '/');
        //         }
        //         else {
        //             checkLogin($response["data"]["message"], true);
        //         }

        //         curl_close($request);
                
        //         header("location: /u21533572/COS216/PA4/index.php?error=none");
        //         exit();
        //     }

        //}

        
        

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