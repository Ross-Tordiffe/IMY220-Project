<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    // $hd_other = '
    //     <div class="event-groups card">
    //         <h3 class="card-header">Collections</h3>
    //         <ul class="overflow-auto list-group list-group-flush">
    //             <li class="list-group-item">Group 1</li>
    //             <li class="list-group-item">Group 2</li>
    //             <li class="list-group-item">Group 3</li>
    //             <li class="list-group-item">Group 4</li>
    //             <li class="list-group-item">Group 5</li>
    //         </ul>
    //     </div>';

    $styles = "<link rel='stylesheet' href='public_html/css/profile.css'>";

    $scripts = "
    <script src='public_html/js/profile.js'></script>";

    require_once("resources/templates/header.php");

    // All friends and friend requests
    $user_friends = array();
    $user_friends_requested = array();
    foreach($_SESSION["user_friends"] as $friend){
        if($friend["status"] == true){
            $user_friends[] = $friend;
        }
        else {
            $user_friends_requested[] = $friend;
        }
    }

?>                          
                <!-- Full container -->
                <div class="profile-container pt-3 h-100 position-absolute">
                    <!-- Profile header -->
                    <div class="profile-header row">
                        <!-- Profile image -->
                        <div class="profile-header-img col-xxl-2 col-2 p-0 m-3">
                            <img src="public_html/img/user/<?=$_SESSION["user_image"]?>" alt="profile image" class="img-fluid">
                        </div>
                        <!-- Profile name and friends -->
                        <div class="profile-header-info col-xxl-9 col-lg-8 p-0 m-3">
                            <div class="profile-header-name">
                                <h3 class="fw-bold"><?=$_SESSION["user_username"]?></h3>
                            </div>
                            <div class="profile-header-friends d-flex align-items-center vertical-align-middle">
                                <span class="friend-count fw-bold"><?php echo(count($user_friends)) ?></span>
                                <span class="friend-text fs-6 p-0 px-1 border-0">Friends</span>
                                <?php 
                                    if(count($user_friends_requested) > 0)
                                    {
                                        // Show friend requests as a round badge notification
                                        echo("<span class='friend-request-count badge rounded-pill'>".count($user_friends_requested)."</span>");
                                        
                                    }
                                ?>
                                
                            </div>
                        </div>
                   
                    </div>
                    <!--Popup list of the user's friends (friends will be appended via js) -->
                    <div class="prifile-friends-list-box d-flex w-100 justify-content-center">
                        <div class="profile-friends-list">
                            <div class="profile-friends-list-header d-flex flex-column align-items-center">
                                <h3 class="fw-bold profile-friends-title py-1">Friends</h3>
                                <div class="profile-friends">

                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" id="logout">Logout</button>
                    <button class="btn btn-primary" id="create-event-button">Create Event</button>
                </div>
                    <!-- Title -->
                    <!-- <h1 class="">Profile</h1> -->
                    <!-- Temporary logout button -->
      
                    <!-- Create event button -->
                    
           
<?php

    require_once("resources/templates/footer.php");

?>