<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $hd_other = '
        <div class="bookmarks">
            <div class="bkm bkm-current" id="bkm-global">

                <!-- BOOKMARK SWAP TO MY EVENTS -->
                <svg class="bkm-tag" width="246" height="116" viewBox="0 0 246 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H246V116H0L55.1379 58L0 0Z" fill="#9F1618"/>
                <rect x="227" width="19" height="116" fill="#821419" fill-opacity="0.75"/>
                </svg>
                <i class="fas fa-user bkm-icon fs-1"></i>
            </div>
            <div class="bkm" id="bkm-local">

                <!-- BOOKMARK SWAP TO GROUPS -->
                <svg class="bkm-tag" width="246" height="116" viewBox="0 0 246 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H246V116H0L55.1379 58L0 0Z" fill="#9F1618"/>
                <rect x="227" width="19" height="116" fill="#821419" fill-opacity="0.75"/>
                </svg>
                <i class="fas fa- bkm-icon fs-1"></i>           
            </div>
        </div>
    ';

    $styles = "<link rel='stylesheet' href='public_html/css/profile.css'>";

    $scripts = "
    <script src='public_html/js/profile.js'></script>";

    
    require_once("resources/templates/header.php");

    $profile_user_id = $_GET["user_id"];

?>                          
                <!-- Full container -->
                <div class="row profile-container table-events event-box">
                    <!-- Profile header -->
                    <div class="col-12 pb-3">
                        <div class="profile-header row">
                            <!-- User Id -->
                            <div id="profile-user-id" class="d-none"><?=$profile_user_id?></div>
                            <!-- Profile image -->
                            <div class="profile-header-img col-xxl-2 col-2 p-3 d-flex align-items-center">
                                <div class="profile-img-container d-flex align-items-center justify-content-center flex-grow-1">
                                    <div class="profile-img-background"></div>
                                    <img src="public_html/img/user/<?=$_SESSION["user_image"]?>" alt="profile image" class="img-fluid">
                                    <div class="profile-header-img-icon position-absolute">
                                    <i class="fas fa-image"></i>
                                    <input type="file" id="profile-img-input" class="d-none" name="profile-img-input">
                                </div>
                                </div>
                                <!-- Profile name and friends -->
                                <!-- hidden change profile image icon-->
                            
                                <div class="profile-header-info d-flex flex-column p-0 m-3 m-xxl-4 flex-shrink-1">
                                    <div class="profile-header-name">
                                        <h3 class="fw-bold"><?=$_SESSION["user_username"]?></h3>
                                    </div>
                                    <div class="profile-header-friends d-flex align-items-center vertical-align-middle">
                                        <span class="friend-count fw-bold">0</span>
                                        <span class="friend-text p-0 px-1 border-0">Friends</span>
                                        <span class='friend-request-count badge rounded-pill d-none fs-6 fs-xxl-5'>0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col offset-8 profile-options">
                                <i class="profile-btn fas fa-cog me-3" id="profile-settings"></i>
                                <i class="profile-btn fas fa-sign-out-alt" id="profile-logout"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-12 event-col-1 event-col"></div>
                    <div class="col-xl-4 col-lg-6 col-0 event-col-2 event-col"></div>
                    <div class="col-xl-4 col-lg-0 col-0 event-col-3 event-col"></div>
                    <!-- Title -->
                    <!-- <h1 class="">Profile</h1> -->
                    <!-- Temporary logout button -->
                    
      
                    <!-- Create event button -->
                    
           
<?php

    require_once("resources/templates/footer.php");
    require_once("resources/templates/modals.php");
?>