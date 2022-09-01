<?php

    // Include the database class
    require_once("resources/config.php");
    require_once("resources/validation/vd-signup.php");
    require_once("resources/validation/vd-login.php");

    $json = file_get_contents("php://input");
    $postData = json_decode($json, true);

    $db = Database::getInstance();

    // Get instance of database class
    // $db = Database::getInstance();

    // function getUser($db, $email, $password){
    //     $stmt = $db->getConnection()->prepare("SELECT `user_password, user_salt` FROM db_users WHERE user_email=?");
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
    
        if(isset($postData['request']) && $postData['request'] == "signup") {
    
            $email = $postData['form']['user_email'];
            $username = $postData['form']['user_username'];
            $password = $postData['form']['user_password'];
            $password_confirm = $postData['form']['user_password_confirm'];
            $firstname = $postData['form']['user_firstname'];
            $lastname = $postData['form']['user_lastname'];
    
            $signup = new SignupHandler($email, $username, $password, $password_confirm, $firstname, $lastname);
            $response = $signup->checkUser();
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$response]));
        }
    
        // if(isset($postData['request'])){
        //     echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>"You did it"]));
        // }

    }

    

    // public function addEvent($user_id, $title, $stime, $etime, $sdate, $edate, $location, $img, $user_count, $description, $category_id){
    //     $stmt = $db->connection->prepare("INSERT INTO db_events (`event_user_id`, ,`lastname` `username`, `email`, `password`) VALUES (?, ?, ?, ?, ?, ?)");
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
    //     $duplicate = $db->getInstance()->prepare("SELECT * FROM `db_users` WHERE `user_email` = ?;");
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