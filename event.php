<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    require_once("resources/templates/header.php");

?>




<?php

    require_once("resources/templates/footer.php");

?>