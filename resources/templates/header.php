<?php

    // Default header variables (if not set)
    $title = $title ?? "";
    $header_display = $header_display ?? "d-block";
    $scripts = $scripts ?? "";
    $styles = $styles ?? "";

    require_once 'resources\config.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>TableTap <?= $title ?></title>

        <!-- Meta Tags -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="author" content="Ross Tordiffe u21533572">
        <link rel="icon" type="image/x-icon" href="/images/favicon.ico">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
        
        <!-- JQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!--Custom Stylesheets -->
        <link rel="stylesheet" href="public_html/css/default.css">
        <?= $styles ?>

        <!--Custom Javascript -->
        <script src="public_html/js/default.js"></script>
        <?= $scripts ?>

    </head>
    <body class="dark">
        <header class="<?=$header_display?>">
            <nav>
                <div class="sidebar-header">Here</div>
            </nav>
        </header>
    
 <!-- ... Content  -->