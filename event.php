<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $styles = "<link rel='stylesheet' href='public_html/css/event.css'>";

    $scripts = "
    <script src='public_html/js/event.js'></script>";

    require_once("resources/templates/header.php");

?>

     <!-- Full container -->
     <div class="row event-container table-events justify-content-center">

<?php

    require_once("resources/templates/footer.php");

?>