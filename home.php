<?php 

    require_once("resources/config.php");
    require_once("resources/templates/header.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

?>

    <h1>
        <?php echo $_SESSION["user_firstname"] . " " . $_SESSION["user_lastname"]; 
            echo $_SESSION["user_email"];
            echo $_SESSION["user_username"];
            echo $_SESSION["user_theme"];
            echo $_SESSION["user_role"];
            echo $_SESSION["user_id"];      
        ?>
    </h1> 

<?php

    require_once("resources/templates/footer.php");

?>