<?php 

    require_once("resources/config.php");
    
    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $hd_other = '
        <div class="bookmarks">
            <div class="bkm bkm-current" id="bkm-global">

                <!-- BOOKMARK SWAP TO GLOBAL -->
                <svg class="bkm-tag" width="246" height="116" viewBox="0 0 246 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H246V116H0L55.1379 58L0 0Z" fill="#9F1618"/>
                <rect x="227" width="19" height="116" fill="#821419" fill-opacity="0.75"/>
                </svg>
                <div class="bkm-icon d-flex flex-column align-items-center">
                    <i class="fa-solid fa-earth-americas"></i>
                    <span class="bkm-icon-text">Global</span>
                </div>
                
            </div>
            <div class="bkm" id="bkm-local">

                <!-- BOOKMARK SWAP TO LOCAL -->
                <svg class="bkm-tag" width="246" height="116" viewBox="0 0 246 116" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0H246V116H0L55.1379 58L0 0Z" fill="#9F1618"/>
                <rect x="227" width="19" height="116" fill="#821419" fill-opacity="0.75"/>
                </svg>
                <div class="bkm-icon d-flex flex-column align-items-center">
                    <i class="fa-solid fa-user-group"></i>
                    <span class="bkm-icon-text">Friends</span>
                </div>
                
            </div>
        </div>
    ';

    $styles = "<link rel='stylesheet' href='public_html/css/home.css'>";

    $scripts = "
    <script src='public_html/js/home.js' defer></script>";

    require_once("resources/templates/header.php");

?>

        <!-- Background -->

        <!-- Events -->

        <div class="row table-events event-box">
            <div class="col-xl-4 col-lg-6 col-12 event-col-1 event-col"></div>
            <div class="col-xl-4 col-lg-6 col-0 event-col-2 event-col"></div>
            <div class="col-xl-4 col-lg-0 col-0 event-col-3 event-col"></div>
        <!-- Create events -->



<?php

    require_once("resources/templates/footer.php");

?>