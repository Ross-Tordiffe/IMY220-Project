<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $styles = "<link rel='stylesheet' href='public_html/css/explore.css'>";

    $scripts = "
    <script src='public_html/js/explore.js'></script>";

    require_once("resources/templates/header.php");

?>

                <div class="explore-container pt-3 h-100 position-absolute pt-5 d-flex flex-column align-items-center">
                    <h3>Gosh you caught us at a bad time</h3>
                    <p>We're still busy here...</p>
                    <!-- under construction -->
                    <i class="fas fa-tools fa-5x pb-3"></i>
                    <p>Come back later!</p>
                    <p>Who knows, maybe we'll have something for you then :)</p>
                </div>
<?php

    require_once("resources/templates/footer.php");

?>