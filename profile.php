<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $hd_other = '
        <div class="event-groups card">
            <h3 class="card-header">Collections</h3>
            <ul class="overflow-auto list-group list-group-flush">
                <li class="list-group-item">Group 1</li>
                <li class="list-group-item">Group 2</li>
                <li class="list-group-item">Group 3</li>
                <li class="list-group-item">Group 4</li>
                <li class="list-group-item">Group 5</li>
            </ul>
        </div>';

    $styles = "<link rel='stylesheet' href='public_html/css/profile.css'>";

    $scripts = "
    <script src='public_html/js/profile.js'></script>
    <script src='public_html/js/eventHandle.js' defer></script>";

    require_once("resources/templates/header.php");

?>                          
                <!-- Full container -->
                <div class="profile-container pt-3 h-100 position-absolute">
                    <!-- Title -->
                    <h1 class="">Profile</h1>
                    <!-- Temporary logout button -->
                    <button class="btn btn-primary" id="logout">Logout</button>
                    <!-- Create event button -->
                    <button class="btn btn-primary" id="create-event-button">Create Event</button>
                </div>
            </div>
           
<?php

    require_once("resources/templates/footer.php");

?>