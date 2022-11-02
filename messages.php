<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $styles = "<link rel='stylesheet' href='public_html/css/messages.css'>";

    $scripts = "
    <script src='public_html/js/messages.js'></script>";

    require_once("resources/templates/header.php");

?>

    <!-- Full container -->
    <div class="row table-events position-absolute h-100 m-0">
        <div class="col-12 messages position-relative h-100">
            <div class="row">
               <div class="col-12 message-header pt-3">
                    <!-- other user profile image and name -->
                    <div class="row p-2 other-user m-0">
                        <div class="col-12 d-flex align-items-center">
                            <div class="back-btn d-flex align-items-center justify-content-center me-3">
                                <i class="fas fa-angle-left"></i>
                            </div>
                            <div class="other-img">
                                <img src="public_html/img/user/user_placeholder_0.svg" alt="profile image" class="other-image">
                            </div>
                            <div class="mb-1 ms-3">
                                <h3 class="other-name"></h3>
                            </div>
                        </div>
                    </div>
               </div> 
            </div>
            <div class="row message-box-container pt-3 pe-2 ps-1">
                <div class="col-12 message-box d-flex flex-column w-100 ">
                    
                </div>
            </div>
            <div class="row position-absolute bottom-0 w-100 ps-1 pb-3">
                <div class="message-input">
                    <div class="row">
                        <div class="col-11 pe-1">
                            <input type="text" class="form-control message-text-input" placeholder="Type a message...">
                        </div>
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <button class="btn btn-primary" id="send-message"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php

    require_once("resources/templates/footer.php");
    require_once("resources/templates/modals.php");

?>