<?php

    // Include the database class
    require_once("resources/config.php");
    require_once("resources/validation/vd-signup.php");
    require_once("resources/validation/vd-login.php");

    $db = Database::getInstance();

    /**
     * Handle all post requests
     */
    if($_SERVER["REQUEST_METHOD"] === "POST"){
    
        // === Main POST requests ===

        /**
         * Handle signup requests
         */
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

        /**
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
                $_SESSION['logged_in'] = true;

                
                echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$response["user_id"]]));
            }
           
        }

        /**
         * Handle logout requests
         */
        if(isset($_POST['request']) && $_POST['request'] === "logout") {

            $session_variables = array_keys($_SESSION);
            foreach($session_variables as $key){
                unset($_SESSION[$key]);
            }

            $_SESSION["logged_in"] = $_SESSION["logged_in"] ?? false;
            $_SESSION["user_role"] = $_SESSION["user_role"] ??  "";
            $_SESSION["user_id"] = $_SESSION["user_id"] ?? "";
            $_SESSION["user_firstname"]= $_SESSION["user_firstname"] ?? "";
            $_SESSION["user_lastname"]= $_SESSION["user_lastname"] ?? "";
            $_SESSION["user_username"] = $_SESSION["user_username"] ?? "";
            $_SESSION["user_email"] = $_SESSION["user_email"] ?? "";

            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>"You did it".$_SESSION["logged_in"]]));
        }

        /**
         * Handle event creation requests
         */
        if(isset($_POST['request']) && $_POST['request'] === "createEvent") {

            $event_image = $_POST['event-file'];
            $event_title = $_POST['event-title'];
            $event_location = $_POST['event-location'];
            $event_website = $_POST['event-website'];
            $event_date = $_POST['event-date'];
            $event_category = $_POST['event-category'];
            $event_description = $_POST['event-description'];
            $event_tags = $_POST['event-tags'];

            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$event_image]));

            $data = [
                "event_image" => $event_image,
                "event_title" => $event_title,
                "event_location" => $event_location,
                "event_website" => $event_website,
                "event_date" => $event_date,
                "event_category" => $event_category,
                "event_description" => $event_description,
                "event_tags" => $event_tags,
            ];

            $message = $db->createEvent($data);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
            
        }

        /**
         * Handle getting of events
         */
        if(isset($_POST['request']) && $_POST['request'] === "getEvents") {
            
            $_POST['profile_user'] = $_POST['profile_user'] ?? $_SESSION['user_id'];

            $events = $db->getEvents($_POST['scope'], $_POST['profile_user']);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$events]));

        };

        // === Secondary POST requests ===

        /**
         * Handle getting friends
         */
        if(isset($_POST['request']) && $_POST['request'] === "getFriends") {
            
            $friends = $db->getFriends($_POST["profile_user"]);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$friends]));
        };

        /**
         * Handle accepting friend requests
         */
        if(isset($_POST['request']) && $_POST['request'] === "acceptFriend") {
            
            $friend_id = $_POST['friend_id'];
            $message = $db->handleFriendRequests($friend_id, true);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle rejecting friend requests
         */
        
        /**
         * Handle getting user data
         */
        if(isset($_POST['request']) && $_POST['request'] === "fetchUserData") {
            
            $get_user_id = $_POST['get_user_id'];
            $user_data = $db->fetchUserData($get_user_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$user_data]));
        };
        
    }
?>