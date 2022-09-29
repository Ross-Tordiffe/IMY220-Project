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
        $_SESSION["user_friends"] = $_SESSION["user_friends"] ?? "";


    }
    
    class Database {

        private static $instance = null;
        private $connection;

        private $servername = "localhost";
        private $username = "root";
        private $password = "";
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
            
            // Get the user from the database
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_email=?");
            $stmt->bind_param("s", $email);
            if($stmt->execute()) {
                $result = $stmt->get_result();
                if($result->num_rows > 0){
                    $row = $result->fetch_assoc();
                    if($row["user_password"] === $password){

                        // Create an object array with the friends and their friendship status of the user from the database
                        $stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_1=?");
                        $stmt->bind_param("i", $row["user_id"]);
                        if($stmt->execute()) {
                            $result = $stmt->get_result();
                            $friends = array();

                            // Get friend request to the user
                            $friend_request_stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_2=? AND fs_accepted=0");
                            $friend_request_stmt->bind_param("i", $row["user_id"]);
                            if($friend_request_stmt->execute()) {
                                $friend_request_result = $friend_request_stmt->get_result();
                                while($friend_request_row = $friend_request_result->fetch_assoc()){
                                    $friends[] = array(
                                        "friend_id" => $friend_request_row["fs_user_id_1"],
                                        "friend_status" => false
                                    );
                                }
                            }
                            
                            if($result->num_rows > 0){
                                while($row2 = $result->fetch_assoc()){
                                    $friends[] = array(
                                        "friend_id" => $row2["fs_user_id_2"], 
                                        "friend_status" => true
                                    );
                                }
                            }
                            $row["user_friends"] = $friends;
                        }

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
                header("Location: index.php/?error=There-was-an-error-logging-in-".$email.$password);
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
                $_SESSION["user_image"] = $user["user_image"];
                $_SESSION["user_theme"] = $user["user_theme"];
                $_SESSION["user_friends"] = $user["user_friends"];
                // header("Location: home.php/");
                return true;
            }   
            
        }

        /**
         * Method to check if the user entry already exists in the database
         */
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
         * Method to create a new event
         */
        public function createEvent($data) {

            $user_id = $_SESSION["user_id"];

            //Get the amount of events the user has created
            $stmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM db_events WHERE event_user_id = ?");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            };

            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $eventCount = $row["COUNT(*)"];

            $stmt->close();

            // prepare upload image to server
            $image = $data["event_image"];
            $image_type = $image["type"];
            $image_name = $user_id."_".$eventCount.".".explode("/", $image_type)[1];
            $image_path = "public_html/img/event/".$image_name;

            // If the image name does not include its specific file type, add it
            if(!strpos($image_name, $image_type)) {
                $image_name = $image_name.".".$image_type;
            }
        
   
            // find category_id using category_name from db_category and insert into db_eventsd
            $category = $this->getConnection()->prepare("SELECT category_id FROM db_category WHERE category_name = ?");
            $category->bind_param("s", $data["event_category"]);
            if(!$category->execute()){
                return false;
            }

            $result = $category->get_result();
            $row = $result->fetch_assoc();
            $categoryID = $row["category_id"];

            $category->close();

            // Set the default user count to 0
            $user_count = 0;

            // If no location set, set to empty string
            if($data["event_location"] == "Add location") {
                $data["event_location"] = "";
            }

            $stmt = $this->getConnection()->prepare("INSERT INTO `db_events` (`event_user_id`, `event_title`, `event_date`, `event_location`, `event_website`, `event_image`, `event_user_count`, `event_description`, `event_category_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssisi", $user_id, $data["event_title"], $data["event_date"], $data["event_location"], $data["event_website"], $image_name, $user_count, $data["event_description"], $categoryID); 
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            // Only upload image if the event was created successfully
            move_uploaded_file($image["tmp_name"], $image_path);

            // insert tags into db_tags
            $tags = explode(" ", $data["event_tags"]);
            $eventID = $this->getConnection()->insert_id;
            foreach($tags as $tag) {
                $tagStmt = $this->getConnection()->prepare("INSERT INTO `db_tags` (`tag_name`) VALUES (?)");
                $tagStmt->bind_param("s", $tag);
                if(!$tagStmt->execute()){
                    return false;
                }
                $tagID = $this->getConnection()->insert_id;
                $eventTagStmt = $this->getConnection()->prepare("INSERT INTO `db_event_tag` (`evttag_event_id`, `evttag_tag_id`) VALUES (?, ?)");
                $eventTagStmt->bind_param("ii", $eventID, $tagID);
                if(!$eventTagStmt->execute()){
                    return false;
                }
            }

            return true;
            
        }

        /**
         * Method to get all the events from the database
         */
        public function getEvents($scope) {
            
           
            if(isset($scope) && $scope == "global") { // If the scope is global then get all the events, sorted by date
                $stmt = $this->getConnection()->prepare("SELECT * FROM db_events ORDER BY event_date DESC");
                if(!$stmt->execute()){
                    return false;
                }
                else {
                    $result = $stmt->get_result();
                    $events = array();
                    while($row = $result->fetch_assoc()) {
                        $events[] = $row;
                    }
                }
            }
            else if (isset($scope) && $scope == "local") { // Otherwise get all of the events created by the user and thier friends
                
                // Get the user's friend ids from the database
                $stmt = $this->getConnection()->prepare("SELECT fs_user_id_2 FROM db_friendships WHERE fs_user_id_1 = ? AND fs_accepted = 1");
                $stmt->bind_param("i", $_SESSION["user_id"]);
                if(!$stmt->execute()){
                    return false;
                }
                $friendIDs = array();
                $result = $stmt->get_result();
                while($row = $result->fetch_assoc()) {
                    $friendIDs[] = $row["fs_user_id_2"];
                }
                $stmt->close();

                // Impolde friend ids into a string to be used in the query
                $friendIDs = implode(",", $friendIDs); 

                // Get the events from the user and their friends
                $stmt = $this->getConnection()->prepare("SELECT * FROM db_events WHERE event_user_id = ? OR event_user_id IN (?) ORDER BY event_date DESC");
                $stmt->bind_param("is", $_SESSION["user_id"], $friendIDs);
                if(!$stmt->execute()){
                    return false;
                }
                $result = $stmt->get_result();
                $events = array();
                while($row = $result->fetch_assoc()) {
                    $events[] = $row;
                }
                $stmt->close();
            }

            if(isset($events)) {

                // Get category name from using the category id for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT category_name FROM db_category WHERE category_id = ?");
                    $stmt->bind_param("i", $event["event_category_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $events[$key]["event_category"] = $row["category_name"];
                    unset($events[$key]["event_category_id"]);
                    $stmt->close();
                }

                //Get user for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                    $stmt->bind_param("i", $event["event_user_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $events[$key]["event_user_image"] = $row["user_image"];
                    $events[$key]["event_user_name"] = $row["user_username"];
                    $stmt->close();
                }

                //Get tags for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_event_tag WHERE evttag_event_id = ?");
                    $stmt->bind_param("i", $event["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $tags = array();
                    while($row = $result->fetch_assoc()) {
                        $tags[] = $row["evttag_tag_id"];
                    }
                    $stmt->close();

                    // Get tag name from using the tag id for each event
                    foreach($tags as $tagKey => $tag) {
                        $stmt = $this->getConnection()->prepare("SELECT tag_name FROM db_tags WHERE tag_id = ?");
                        $stmt->bind_param("i", $tag);
                        if(!$stmt->execute()){
                            return false;
                        }
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $tags[$tagKey] = $row["tag_name"];
                        $stmt->close();
                    }
                    $events[$key]["event_tags"] = $tags;
                }

                return $events;
            }
            else if(isset($scope) && $scope == "profile") {

          

                $stmt = $this->getConnection()->prepare("SELECT * FROM db_events WHERE event_user_id = ? ORDER BY event_date DESC");
                $stmt->bind_param("i", $_SESSION["user_id"]);
                if(!$stmt->execute()){
                    return false;
                }
                $result = $stmt->get_result();
                $events = array();
                while($row = $result->fetch_assoc()) {
                    $events[] = $row;
                }
                $stmt->close();

                // Get category name from using the category id for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT category_name FROM db_category WHERE category_id = ?");
                    $stmt->bind_param("i", $event["event_category_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $events[$key]["event_category"] = $row["category_name"];
                    unset($events[$key]["event_category_id"]);
                    $stmt->close();
                }

                //Get user for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                    $stmt->bind_param("i", $event["event_user_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();
                    $events[$key]["event_user_image"] = $row["user_image"];
                    $events[$key]["event_user_name"] = $row["user_username"];
                    $stmt->close();
                }

                //Get tags for each event
                foreach($events as $key => $event) {
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_event_tag WHERE evttag_event_id = ?");
                    $stmt->bind_param("i", $event["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $tags = array();
                    while($row = $result->fetch_assoc()) {
                        $tags[] = $row["evttag_tag_id"];
                    }
                    $stmt->close();
                    $events[$key]["event_tags"] = $tags;
                }
  
                return $events;
            }
            else { // If the scope is not set or is invalid then return false
                return false;
            }
        } 

        /**
         * Method to get the ids, names and images of the users friends
         */
        public function getFriends() {

            $friends = array();
            $user_id = $_SESSION["user_id"];
            $row = array();

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_2 = ? AND fs_accepted = 0");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $request_result = $stmt->get_result();

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_1 = ?");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $friend_result = $stmt->get_result();

            // Append friend requests and friends to the friends array
            while($row = $request_result->fetch_assoc()) {
                $request_id = $row["fs_user_id_1"];
                $request_stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                $request_stmt->bind_param("i", $request_id);
                if(!$request_stmt->execute()){
                    return false;
                }
                $request_result = $request_stmt->get_result();
                $request_row = $request_result->fetch_assoc();
                $friends[] = array("user_id" => $request_row["user_id"], "user_username" => $request_row["user_username"], "user_image" => $request_row["user_image"], "accepted" => false);
            }
            
            while($row = $friend_result->fetch_assoc()) {
                $friend_id = $row["fs_user_id_2"];
                $friend_stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                $friend_stmt->bind_param("i", $friend_id);
                if(!$friend_stmt->execute()){
                    return false;
                }
                $friend_result = $friend_stmt->get_result();
                $friend_row = $friend_result->fetch_assoc();
                $friends[] = array("user_id" => $friend_row["user_id"], "user_username" => $friend_row["user_username"], "user_image" => $friend_row["user_image"], "accepted" => true);
            }

            return $friends;
        }
        
    }

    $db = Database::getInstance(); 
?>