<?php

    // Include the database class
    require_once("resources/config.php");
    require_once("resources/validation/vd-signup.php");
    require_once("resources/validation/vd-login.php");

    $db = Database::getInstance();

    // Get instance of database class
    // $db = Database::getInstance();

    // function getUser($db, $email, $password){
    //     $stmt = $db->getConnection()->prepare("SELECT `su-password, user_salt` FROM db_users WHERE user_email=?");
    //     $stmt->bind_param("s", $email);
    //     if($stmt->execute()){
    //         $result = $stmt->get_result();
    //         if($result->num_rows > 0){
    //             $row = $result->fetch_assoc();
    //             if($row["user_password"] === $password){
    //                 return $row;
    //             }
    //             else {
    //                 return false;
    //             }
    //         }
    //         else {
    //             return false;
    //         }
    //     }
    //     else {
    //         return false;
    //     }
    // }

    /**
     * Handle post requests
     */
    if($_SERVER["REQUEST_METHOD"] === "POST"){

        if(isset($postData['request']) && $postData['request'] == "login") {
            $email = $postData['email'];
            $pass = $postData['pass'];
            $login = new LoginHandler($email, $pass);
            $login->checkUser();
        }
    
        // echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>"You did it"]));
    
        if(isset($_POST['request']) && $_POST['request'] === "signup") {
    
            $email = $_POST['su-email'];
            $username = $_POST['su-username'];
            $password = $_POST['su-password'];
            $password_confirm = $_POST['su-password-confirm'];
            $firstname = $_POST['su-firstname'];
            $lastname = $_POST['su-lastname'];
            
            $signup = new SignupHandler($email, $username, $password, $password_confirm, $firstname, $lastname);
            $response = json_decode($signup->checkUser(), true);

            if($response["status"] === "error"){
                
                echo(json_encode(["status"=>"error", "timestamp"=>time(), "data"=>$response["message"]]));
            }
            else {
                echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$response["user_id"]]));
            }
           
        }

        /**e
         * Handle login requests
         */
        if(isset($_POST['request']) && $_POST['request'] === "login") {
    
            $email = $_POST['lg-email'];
            $password = $_POST['lg-password'];
            
            $login = new LoginHandler($email, $password);
            $response = json_decode($login->checkUser(), true);

            if($response["status"] === "error"){
                
                echo(json_encode(["status"=>"error", "timestamp"=>time(), "data"=>$response["message"]]));
            }
            else {
                echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$response["user_id"]]));
            }
           
        }
    
        // if(isset($postData['request'])){
        //     echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>"You did it"]));
        // }

    }

    

    // public function addEvent($su-id, $title, $stime, $etime, $sdate, $edate, $location, $img, $su-count, $description, $category_id){
    //     $stmt = $db->connection->prepare("INSERT INTO db_events (`event_su-id`, ,`lastname` `username`, `email`, `password`) VALUES (?, ?, ?, ?, ?, ?)");
    //     $stmt->bind_param("ssssss", $firstname, $lastname, $username, $email, $password);

    //     //If statement executed successfully
    //     if($stmt->execute()){
    //         return $db->getConnection()->insert_id;
    //     }
    //     else{
    //         return false;
    //     }
    // }

    // protected function duplicateCheck($email){
    //     $duplicate = $db->getInstance()->prepare("SELECT * FROM `db_users` WHERE `su-email` = ?;");
    //     if(!$duplicate->execute(array($email))){
    //         $duplicate = null;

    //         header("location: index.php?error=There-was-an-error-looking-for-duplicate-users.");
    //         exit();
    //     }

    //     if($duplicate->num_rows() > 0)
    //         $duplicate_result = false;
    //     else
    //         $duplicate_result = true;
            
    //     return $duplicate_result;
    // }

?>