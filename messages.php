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
    <div class="row table-events">
        <div class="col-12 message-box d-flex flex-column w-100">

<?php

    require_once("resources/templates/footer.php");
    require_once("resources/templates/modals.php");

?>