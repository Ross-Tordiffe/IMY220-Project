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

            $event_image = $_FILES['event-file'];
            $event_title = $_POST['event-title'];
            $event_location = $_POST['event-location'];
            $event_website = $_POST['event-website'];
            $event_date = $_POST['event-date'];
            $event_category = $_POST['event-category'];
            $event_description = $_POST['event-description'];
            $event_tags = $_POST['event-tags'];

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
         * Handle Updating of events
         */
        if(isset($_POST['request']) && $_POST['request'] === "updateEvent") {

            $event_id = $_POST['event-id'];
            $event_image = $_FILES['event-file'];
            $event_title = $_POST['event-title'];
            $event_location = $_POST['event-location'];
            $event_website = $_POST['event-website'];
            $event_date = $_POST['event-date'];
            $event_category = $_POST['event-category'];
            $event_description = $_POST['event-description'];
            $event_tags = $_POST['event-tags'];

            $data = [
                "event_id" => $event_id,
                "event_image" => $event_image,
                "event_title" => $event_title,
                "event_location" => $event_location,
                "event_website" => $event_website,
                "event_date" => $event_date,
                "event_category" => $event_category,
                "event_description" => $event_description,
                "event_tags" => $event_tags,
            ];

            $message = $db->updateEvent($data);
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

        /**
         * Handle getting of a single event using the event id
         */
        if(isset($_POST['request']) && $_POST['request'] === "getEvent") {
            
            $event = $db->getEvent($_POST['event_id']);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$event]));

        };

        /**
         * Handle removing of an event
         */
        if(isset($_POST['request']) && $_POST['request'] === "deleteEvent") {
            
            $event = $db->deleteEvent($_POST['event_id']);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$event]));
        };

        /**
         * Handle getting of all users
         */
        if(isset($_POST['request']) && $_POST['request'] === "getUsers") {
            
            $users = $db->getUsers();
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$users]));
            
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
         * Handle friendRequest
         */
        if(isset($_POST['request']) && $_POST['request'] === "friendRequest") {
            $friend_id = $_POST['friend_id'];
            $message = $db->sendFriendRequest($friend_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle removeFriend
         */
        if(isset($_POST['request']) && $_POST['request'] === "removeFriend") {
            $friend_id = $_POST['friend_id'];
            $message = $db->removeFriend($friend_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
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
        if(isset($_POST['request']) && $_POST['request'] === "rejectFriend") {
            
            $friend_id = $_POST['friend_id'];
            $message = $db->handleFriendRequests($friend_id, false);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };
        
        /**
         * Handle getting user data
         */
        if(isset($_POST['request']) && $_POST['request'] === "fetchUserData") {
            $user_id = $_POST['user_id'];
            $user_data = $db->fetchUserData($user_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$user_data]));
        };

        /**
         * Handle getting reviews for event
         */
        if(isset($_POST['request']) && $_POST['request'] === "getReviews") {
            $event_id = $_POST['event_id'];
            $reviews = $db->fetchReviews($event_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$reviews]));
        };

        /**
         * Handle create review
         */
        if(isset($_POST['request']) && $_POST['request'] === "createReview") {
            $review_event_id = $_POST['review_event_id'];
            $review_user = $_POST['review_user_id'];
            $review_message = $_POST['review_message'];
            $review_rating = $_POST['review_rating'];
            $review_image = $_FILES['review_image_file'] ?? null;

            $message = $db->createReview($review_event_id, $review_user, $review_message, $review_rating, $review_image);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle creating of groups of events like a playlist
         */
        if(isset($_POST['request']) && $_POST['request'] === "createGroup") {
            $group_name = $_POST['group_name'];
            $group_description = $_POST['group_description'];

            $message = $db->createGroup($group_name, $group_description);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle getting of groups
         */
        if(isset($_POST['request']) && $_POST['request'] === "getGroups") {
            $groups = $db->getGroups();
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$groups]));
        };

        /**
         * Handle getting of a single group
         */
        if(isset($_POST['request']) && $_POST['request'] === "getGroup") {
            $group_id = $_POST['group_id'];
            $group = $db->getGroup($group_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$group]));
        };

        /**
         * Handle deleting of a group
         */
        if(isset($_POST['request']) && $_POST['request'] === "deleteGroup") {
            $group_id = $_POST['group_id'];
            $message = $db->deleteGroup($group_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };        

        /**
         * Handle adding of an event to a group
         */
        if(isset($_POST['request']) && $_POST['request'] === "addEventToGroup") {
            $group_id = $_POST['group_id'];
            $event_id = $_POST['event_id'];
            $message = $db->addEventToGroup($group_id, $event_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle removing of an event from a group
         */
        if(isset($_POST['request']) && $_POST['request'] === "removeEventFromGroup") {
            $group_id = $_POST['group_id'];
            $event_id = $_POST['event_id'];
            $message = $db->removeEventFromGroup($group_id, $event_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle attending of an event
         */
        if(isset($_POST['request']) && $_POST['request'] === "attendEvent") {
            $event_id = $_POST['event_id'];
            $message = $db->attendEvent($event_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle checking if user is attending an event
         */
        if(isset($_POST['request']) && $_POST['request'] === "isAttendingEvent") {
            $event_id = $_POST['event_id'];
            $message = $db->isAttendingEvent($event_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle changing of user profile picture
         */
        if(isset($_POST['request']) && $_POST['request'] === "changeProfilePicture") {
            $profile_picture = $_FILES['file'];
            // Make the file name the user id and the file extension
            $file_name = $_SESSION['user_id'] . "." . pathinfo($profile_picture['name'], PATHINFO_EXTENSION);
            $message = $db->changeProfilePicture($file_name, $profile_picture);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle getting of messages
         */
        if(isset($_POST['request']) && $_POST['request'] === "getMessages") {

            $other_user_id = $_POST['other_user_id'];
            $messages = $db->getMessages($other_user_id);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$messages]));
        };

        /**
         * Handle sending of messages
         */
        if(isset($_POST['request']) && $_POST['request'] === "sendMessage") {
            $other_user_id = $_POST['other_user_id'];
            $message = $_POST['message'];
            $message = $db->sendMessage($other_user_id, $message);
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$message]));
        };

        /**
         * Handle getting unread status of messages from friends
        */
        if(isset($_POST['request']) && $_POST['request'] === "getUnreadMessages") {
            $messages = $db->getUnreadMessages();
            echo(json_encode(["status"=>"success", "timestamp"=>time(), "data"=>$messages]));
        };
    }
?>