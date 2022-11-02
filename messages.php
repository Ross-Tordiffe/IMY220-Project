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
    <div class="row table-events position-absolute h-100 m-0 pb-3">
        <div class="col-12 messages position-relative h-100">
            <div class="row">
               <div class="col-12 message-header">
                    <!-- other user profile image and name -->
                    <div class="row">
                        <div class="col-2">
                            <img src="public_html/img/user/user_placeholder_0" alt="profile image" class="other-image">
                        </div>
                        <div class="col-10">
                            <h3 class="other-name"></h3>
                        </div>
                    </div>
               </div> 
            </div>
            <div class="row">
                <div class="col-12 message-box d-flex flex-column w-100 ">
                    
                </div>
            </div>
            <div class="row position-absolute bottom-0 w-100 px-3">
                <div class="message-input">
                    <!-- include file input button (paperclip), textbox and send button (fixed to bottom)-->
                    <div class="row">
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <button class="btn btn-primary"><i class="fas fa-paperclip"></i></button>
                        </div>
                        <div class="col-10">
                            <input type="text" class="form-control message-text-input" placeholder="Type a message...">
                        </div>
                        <div class="col-1 d-flex align-items-center justify-content-center">
                            <button class="btn btn-primary" id="send-message"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
   
        <!-- include file input, textbox and send button (stick to bottom of row-->
     
    
<?php

    require_once("resources/templates/footer.php");
    require_once("resources/templates/modals.php");

?>