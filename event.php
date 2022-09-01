<?php 

    require_once("resources/config.php");
    require_once("resources/templates/header.php");

    if($_SESSION["logged_in"] == false){
        header("Location: index.php");
    }

?>




<?php

    require_once("resources/templates/footer.php");

?>