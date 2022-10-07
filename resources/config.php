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

        // private $servername = "localhost";
        // private $username = "u21533572";
        // private $password = "drgdxgld";
        // private $db = "u21533572"; 
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
                $_SESSION["user_friends"] = "";
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
            // if(!strpos($image_name, $image_type)) {
            //     $image_name = $image_name.".".$image_type;
            // }
        
   
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

            // insert tags into db_tags only if they do not already exist, otherwise get the tag_id and insert into db_event_tags
            $tags = explode(" ", $data["event_tags"]);
            $eventID = $this->getConnection()->insert_id;
            foreach($tags as $tag) {

                // Check if the tag already exists
                $tagCheck = $this->getConnection()->prepare("SELECT * FROM `db_tags` WHERE tag_name = ?");
                $tagCheck->bind_param("s", $tag);
                if(!$tagCheck->execute()){
                    return false;
                }
                $result = $tagCheck->get_result();
                $row = $result->fetch_assoc();
                $tagID = isset($row["tag_id"]) ? $row["tag_id"] : null;
                $tagCheck->close();

                // If the tag does not exist, create it
                if($tagID == null) {
                    $tagCreate = $this->getConnection()->prepare("INSERT INTO db_tags (`tag_name`) VALUES (?)");
                    $tagCreate->bind_param("s", $tag);
                    if(!$tagCreate->execute()){
                        return false;
                    }
                    $tagID = $this->getConnection()->insert_id;
                    $tagCreate->close();
                }

                

                $tagInsert = $this->getConnection()->prepare("INSERT INTO db_event_tag (`evttag_event_id`, `evttag_tag_id`) VALUES (?, ?)");
                $tagInsert->bind_param("ii", $eventID, $tagID);
                if(!$tagInsert->execute()){
                    return false;
                }
                $tagInsert->close();
                
            }

            return true;
            
        }

        /**
         * Method to update an event
         */
        public function updateEvent($data) {

            if($data["event_location"] == "Add location") {
                $data["event_location"] = "";
            }

            // === Get the original event ===
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_events WHERE event_id = ?");
            $stmt->bind_param("i", $data["event_id"]);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
        
            $stmt->close();
   
            // === Get the original image name ===
            $image_name = $row["event_image"];

            // === Get the original category ===
            $category = $this->getConnection()->prepare("SELECT category_name FROM db_category WHERE category_id = ?");
            $category->bind_param("i", $row["event_category_id"]);
            if(!$category->execute()){
                return false;
            }
            $result = $category->get_result();
            $row = $result->fetch_assoc();
            $original = $row;
            $category_name = $row["category_name"];
            $category->close();

            // === Get the original tags and tag ids===
            $originalTags = $this->getConnection()->prepare("SELECT tag_name, tag_id FROM db_tags INNER JOIN db_event_tag ON db_tags.tag_id = db_event_tag.evttag_tag_id WHERE db_event_tag.evttag_event_id = ?");
            $originalTags->bind_param("i", $data["event_id"]);
            if(!$originalTags->execute()){
                return false;
            }
            $result = $originalTags->get_result();
            $originalTagsArray = array();
            $originalTagIDs = array();
            while($row = $result->fetch_assoc()) {
                array_push($originalTagsArray, $row["tag_name"]);
                array_push($originalTagIDs, $row["tag_id"]);
            }
            $originalTags->close();

            // === Check if the image was changed ===
            if($data["event_image"]["name"] != "") {
                // === If the image was changed, delete the old image ===
                unlink("public_html/img/event/".$image_name);

                // === Upload the new image ===
                $image = $data["event_image"];
                $image_type = $image["type"];
                $image_name = $data["event_id"]."_".explode("/", $image_type)[1];
                $image_path = "public_html/img/event/".$image_name;
                move_uploaded_file($image["tmp_name"], $image_path);
            }

            // === Update the event ===
            $stmt = $this->getConnection()->prepare("UPDATE `db_events` SET `event_title` = ?, `event_date` = ?, `event_location` = ?, `event_website` = ?, `event_image` = ?, `event_description` = ? WHERE `db_events`.`event_id` = ?");
            $stmt->bind_param("ssssssi", $data["event_title"], $data["event_date"], $data["event_location"], $data["event_website"], $image_name, $data["event_description"], $data["event_id"]);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            // === Delete all the tags not included in the new tag list ===
            $tags = explode(" ", $data["event_tags"]);
            foreach($originalTagsArray as $tag) {
                if(!in_array($tag, $tags)) {
                    $tagID = array_search($tag, $originalTagsArray);
                    $tagID = $originalTagIDs[$tagID];
                    $deleteTag = $this->getConnection()->prepare("DELETE FROM `db_event_tag` WHERE `db_event_tag`.`evttag_tag_id` = ?");
                    $deleteTag->bind_param("i", $tagID);
                    if(!$deleteTag->execute()){
                        return false;
                    }
                    $deleteTag->close();
                }
            }

            // === Insert the new tags into the db_tags table ===
            foreach($tags as $tag) {
                if(!in_array($tag, $originalTagsArray)) {
                    $tagStmt = $this->getConnection()->prepare("INSERT INTO `db_tags` (`tag_name`) VALUES (?)");
                    $tagStmt->bind_param("s", $tag);
                    if(!$tagStmt->execute()){
                        return false;
                    }
                    $tagID = $this->getConnection()->insert_id;
                    $eventTagStmt = $this->getConnection()->prepare("INSERT INTO `db_event_tag` (`evttag_event_id`, `evttag_tag_id`) VALUES (?, ?)");
                    $eventTagStmt->bind_param("ii", $data["event_id"], $tagID);
                    if(!$eventTagStmt->execute()){
                        return false;
                    }
                }
            }

            // === if website was changed, clear the event_location field ===
            if((isset($original["event_website"]) && $original["event_website"] != "") && ( ($data["event_website"] != $original["event_website"]) || ($original["event_location"] == "Add location") ) ) {
                $stmt = $this->getConnection()->prepare("UPDATE `db_events` SET `event_location` = '' WHERE `db_events`.`event_id` = ?");
                $stmt->bind_param("i", $data["event_id"]);
                if(!$stmt->execute()){
                    return false;
                }
                $stmt->close();
            }
            else {
                // === if website was not changed, check if location was changed ===
                if(isset($original["event_location"]) && $data["event_location"] != $original["event_location"]) {
                    // === if location was changed, clear the event_website field ===
                    $stmt = $this->getConnection()->prepare("UPDATE `db_events` SET `event_website` = '' WHERE `db_events`.`event_id` = ?");
                    $stmt->bind_param("i", $data["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $stmt->close();
                }
            }

            return true;
        }


        /**
         * Method to get all the events from the database
         */
        public function getEvents($scope, $profile_user) {
            
           
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

                // Get the user count for each event from the db_booking table
                foreach($events as $key => $event) {
                
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_booking WHERE bk_event_id = ?");
                    $stmt->bind_param("i", $event["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $events[$key]["event_user_count"] = $result->num_rows;
                    $stmt->close();
                }
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

                // Get the user count for each event from the db_booking table
                foreach($events as $key => $event) {
                
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_booking WHERE bk_event_id = ?");
                    $stmt->bind_param("i", $event["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $events[$key]["event_user_count"] = $result->num_rows;
                    $stmt->close();
                }

                return $events;
            }
            else if(isset($scope) && $scope == "profile") {

                $stmt = $this->getConnection()->prepare("SELECT * FROM db_events WHERE event_user_id = ? ORDER BY event_date DESC");
                $stmt->bind_param("i", $profile_user);
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
            
                // Get the user count for each event from the db_booking table
                foreach($events as $key => $event) {
                
                    $stmt = $this->getConnection()->prepare("SELECT * FROM db_booking WHERE bk_event_id = ?");
                    $stmt->bind_param("i", $event["event_id"]);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $result = $stmt->get_result();
                    $events[$key]["event_user_count"] = $result->num_rows;
                    $stmt->close();
                }

                return $events;
            }
            else { // If the scope is not set or is invalid then return false
                return false;
            }
        } 

        /**
         * Method to get a single event from the database using the event id
         */
        public function getEvent($event_id) {

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_events WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $event = $result->fetch_assoc();
            $stmt->close();

            // Get category name from using the category id for the event
            $stmt = $this->getConnection()->prepare("SELECT category_name FROM db_category WHERE category_id = ?");
            $stmt->bind_param("i", $event["event_category_id"]);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $event["event_category"] = $row["category_name"];
            unset($event["event_category_id"]);
            $stmt->close();

            // Get user for the event
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
            $stmt->bind_param("i", $event["event_user_id"]);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $event["event_user_image"] = $row["user_image"];
            $event["event_user_name"] = $row["user_username"];
            $stmt->close();

            // Get tags for the event
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

            // Get tag name from using the tag id for the event
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
            $event["event_tags"] = $tags;

            // Get the user count for the event
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_booking WHERE bk_event_id = ?");
            $stmt->bind_param("i", $event["event_id"]);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $event["event_user_count"] = $result->num_rows;

            return $event;
        }

        /**
         * Method to delete and event and its image from the database using the event id
         */
        public function deleteEvent($event_id) {

            // Delete the image from the server
            $image = $this->getEvent($event_id)["event_image"];

            if($image != null) {
                unlink("public_html/img/event/".$image);
            }

            // Delete the event from the database
            $stmt = $this->getConnection()->prepare("DELETE FROM db_events WHERE event_id = ?");
            $stmt->bind_param("i", $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            $tagIds = array();

            // Get the tag ids for the event
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_event_tag WHERE evttag_event_id = ?");
            $stmt->bind_param("i", $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()) {
                $tagIds[] = $row["evttag_tag_id"];
            }
            $stmt->close();
            
            $stmt = $this->getConnection()->prepare("DELETE FROM db_event_tag WHERE evttag_event_id = ?");
            $stmt->bind_param("i", $event_id);
            if(!$stmt->execute()){
                return false;
            }            
            $stmt->close();

            // Delete the tags from the database if they are not used by any other event
            foreach($tagIds as $tagId) {
                $stmt = $this->getConnection()->prepare("SELECT * FROM db_event_tag WHERE evttag_tag_id = ?");
                $stmt->bind_param("i", $tagId);
                if(!$stmt->execute()){
                    return false;
                }
                $result = $stmt->get_result();
                if($result->num_rows == 0) {
                    $stmt = $this->getConnection()->prepare("DELETE FROM db_tags WHERE tag_id = ?");
                    $stmt->bind_param("i", $tagId);
                    if(!$stmt->execute()){
                        return false;
                    }
                    $stmt->close();
                }
            }

            return true;
            
        }

        /**
         * Method to get the ids, names and images of the users friends
         */
        public function getFriends($profile_user) {

            $friends = array();
            $user_id = $profile_user;
            $row = array();

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_2 = ? AND fs_accepted = 0");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $request_result = $stmt->get_result();

            $stmt->close();

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_friendships WHERE fs_user_id_1 = ?");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $friend_result = $stmt->get_result();

            // Append friend requests and friends to the friends array
            while($row1 = $request_result->fetch_assoc()) {
                $request_id = $row1["fs_user_id_1"];
                $request_stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                $request_stmt->bind_param("i", $request_id);
                if(!$request_stmt->execute()){
                    return false;
                }
                $result = $request_stmt->get_result();
                $request_row = $result->fetch_assoc();
                $friends[] = array("user_id" => $request_row["user_id"], "user_username" => $request_row["user_username"], "user_image" => $request_row["user_image"], "accepted" => false);
            }
            
            while($row2 = $friend_result->fetch_assoc()) {
                $friend_id = $row2["fs_user_id_2"];

                $friend_stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
                $friend_stmt->bind_param("i", $friend_id);
                if(!$friend_stmt->execute()){
                    return false;
                }
                $result = $friend_stmt->get_result();
                $friend_row = $result->fetch_assoc();
                array_push($friends, array("user_id" => $friend_row["user_id"], "user_username" => $friend_row["user_username"], "user_image" => $friend_row["user_image"], "accepted" => true));
            }

            $_SESSION["user_friends"] = $friends;
            return $friends;
        }
        
        /**
         * Method to send a friend request to another user
         */
        public function sendFriendRequest($friend_id) {

            $user_id = $_SESSION["user_id"];

            $stmt = $this->getConnection()->prepare("INSERT INTO db_friendships (fs_user_id_1, fs_user_id_2, fs_accepted) VALUES (?, ?, 0)");
            $stmt->bind_param("ii", $user_id, $friend_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();
            return true;
        }

        /**
         * Method to remove a friend from the user's friends list
         */
        public function removeFriend($friend_id) {

            $user_id = $_SESSION["user_id"];

            $stmt = $this->getConnection()->prepare("DELETE FROM db_friendships WHERE fs_user_id_1 = ? AND fs_user_id_2 = ?");
            $stmt->bind_param("ii", $user_id, $friend_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            $stmt = $this->getConnection()->prepare("DELETE FROM db_friendships WHERE fs_user_id_1 = ? AND fs_user_id_2 = ?");
            $stmt->bind_param("ii", $friend_id, $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            return true;
        }

        
        /**
         * Method to accept or refuse a friend request and update the friendship in the database
         */
        public function handleFriendRequests($friend_id, $accepted)
        {

            // Friend request accepted
            if($accepted) {

                $stmt = $this->getConnection()->prepare("UPDATE db_friendships SET fs_accepted = 1 WHERE fs_user_id_1 = ? AND fs_user_id_2 = ?");
                $stmt->bind_param("ii", $friend_id, $_SESSION["user_id"]);
                if(!$stmt->execute()){
                    return false;
                }

                $stmt = $this->getConnection()->prepare("INSERT INTO db_friendships (fs_user_id_1, fs_user_id_2, fs_accepted) VALUES (?, ?, 1)");
                $stmt->bind_param("ii", $_SESSION["user_id"], $friend_id);
                if(!$stmt->execute()){
                    return false;
                }
                
                return true;

            }
            else {

                $stmt = $this->getConnection()->prepare("DELETE FROM db_friendships WHERE fs_user_id_1 = ? AND fs_user_id_2 = ?");
                $stmt->bind_param("ii", $friend_id, $_SESSION["user_id"]);
                if(!$stmt->execute()){
                    return false;
                }

                return true;
            }
            
        }

        /**
         * Method to fetch basic information about a user
         */
        public function fetchUserData($user_id) {

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_users WHERE user_id = ?");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            $user = array("user_id" => $row["user_id"], "user_username" => $row["user_username"], "user_image" => $row["user_image"]);
            
            return $user;
        }

        /**
         * Method to fetch reviews for an event
        */
        public function fetchReviews($event_id) {

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_review WHERE review_event_id = ? ORDER BY review_id DESC");
            $stmt->bind_param("i", $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $reviews = array();
            while($row = $result->fetch_assoc()) {
                $reviews[] = array("review_id" => $row["review_id"], "review_user_id" => $row["review_user_id"], "review_event_id" => $row["review_event_id"], "review_message" => $row["review_message"], "review_rating" => $row["review_rating"], "review_time" => $row["review_time"], "review_image" => $row["review_image"]);
            }
            $stmt->close();

            // Fetch user data for each review
            foreach($reviews as &$review) {
                $user = $this->fetchUserData($review["review_user_id"]);
                $review["review_user_username"] = $user["user_username"];
                $review["review_user_image"] = $user["user_image"];
            }

            return $reviews;
        }

        /**
         * Method to create review for an event
         */
        public function createReview($review_event_id, $review_user, $review_review, $review_rating, $review_image) {

            // Get the number of reviews for the event
            $stmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM db_review WHERE review_event_id = ?");
            $stmt->bind_param("i", $review_event_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $review_number = $row["COUNT(*)"];
            $stmt->close();

            // prepare upload image to server
            if(isset($review_image)){
                $review_image_type = $review_image["type"];
                $review_image_name = $review_user."_".$review_number.".".explode("/", $review_image_type)[1];
                $review_image_path = "public_html/img/review/".$review_image_name;
            }
            else {
                $review_image_name = "";
            }

            $stmt = $this->getConnection()->prepare("INSERT INTO db_review (review_event_id, review_user_id, review_message, review_rating, review_image) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $review_event_id, $review_user, $review_review, $review_rating, $review_image_name);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            $review_id = $this->getConnection()->insert_id;
            $review_time = time();

            if(isset($review_image)) {
                move_uploaded_file($review_image["tmp_name"], $review_image_path);
            }

            // return the created review
            

            $review[] = array("review_id" => $review_id, "review_user_id" => $review_user, "review_event_id" => $review_event_id, "review_message" => $review_review, "review_rating" => $review_rating, "review_time" => $review_time, "review_image" => $review_image_name);
            $review[0]["review_user_username"] = $_SESSION["user_username"];
            $review[0]["review_user_image"] = $_SESSION["user_image"];

            return $review;
        }

        /**
         * Method to create a group of events like a playlist
         */
        public function createGroup($group_name, $group_description) {

            $user_id = $_SESSION["user_id"];
            
            $stmt = $this->getConnection()->prepare("INSERT INTO db_list (list_user_id, list_title, list_description) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $group_name, $group_description);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();
        }

        /**
         * Method to fetch groups for a user
         */
        public function getGroups() {

            $user_id = $_SESSION["user_id"];

            // Get all groups' information
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_list WHERE list_user_id = ?");
            $stmt->bind_param("i", $user_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $groups = array();
            while($row = $result->fetch_assoc()) {
                $groups[] = array("group_id" => $row["list_id"], "group_user_id" => $row["list_user_id"], "group_title" => $row["list_title"], "group_description" => $row["list_description"]);
            }
            
            // Get all events' information for each group
            foreach($groups as &$group) {
                $stmt = $this->getConnection()->prepare("SELECT * FROM db_list_event WHERE listevt_list_id = ?");
                $stmt->bind_param("i", $group["group_id"]);
                if(!$stmt->execute()){
                    return false;
                }
                $result = $stmt->get_result();
                $events = array();
                while($row = $result->fetch_assoc()) {
                    $events[] = $this->getEvent($row["listevt_event_id"]);
                }
                $group["group_events"] = $events;
            }

            return $groups;

        }

        /**
         * Method to get a specific group
         */
        public function getGroup($group_id) {

            $user_id = $_SESSION["user_id"];

            // Get group's information
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_list WHERE list_id = ?");
            $stmt->bind_param("i", $group_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $group = array("group_id" => $row["list_id"], "group_user_id" => $row["list_user_id"], "group_title" => $row["list_title"], "group_description" => $row["list_description"]);
            
            // Get all events' information for the group
            $stmt = $this->getConnection()->prepare("SELECT * FROM db_list_event WHERE listevt_list_id = ?");
            $stmt->bind_param("i", $group_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            $events = array();
            while($row = $result->fetch_assoc()) {
                $events[] = $this->getEvent($row["listevt_event_id"]);
            }
            $group["group_events"] = $events;

            return $group;

        }

        /**
         * Method to add an event to a group
         */
        public function addEventToGroup($group_id, $event_id) {

            $stmt = $this->getConnection()->prepare("INSERT INTO db_list_event (listevt_list_id, listevt_event_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $group_id, $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            return true;
        }

        /**
         * Method to delete a group
         */
        public function deleteGroup($group_id) {

            $stmt = $this->getConnection()->prepare("DELETE FROM db_list WHERE list_id = ?");
            $stmt->bind_param("i", $group_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            return true;

        }

        /**
         * Method to delete an event from a group
         */
        public function removeEventFromGroup($group_id, $event_id) {

            $stmt = $this->getConnection()->prepare("DELETE FROM db_list_event WHERE listevt_list_id = ? AND listevt_event_id = ?");
            $stmt->bind_param("ii", $group_id, $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            return true;

        }

        /**
         * Method attend (book) an event
         */
        public function attendEvent($event_id) {

            $user_id = $_SESSION["user_id"];

            $stmt = $this->getConnection()->prepare("INSERT INTO db_booking (bk_user_id, bk_event_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $user_id, $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $stmt->close();

            return true;
    
        }

        /**
         * Method to check if a user is attending an event
         */
        public function isAttendingEvent($event_id) {

            $user_id = $_SESSION["user_id"];

            $stmt = $this->getConnection()->prepare("SELECT * FROM db_booking WHERE bk_user_id = ? AND bk_event_id = ?");
            $stmt->bind_param("ii", $user_id, $event_id);
            if(!$stmt->execute()){
                return false;
            }
            $result = $stmt->get_result();
            if($result->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    $db = Database::getInstance(); 
?>