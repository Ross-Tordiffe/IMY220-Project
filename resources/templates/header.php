<?php

    // Default header variables (if not set)
    $title = $title ?? "";
    $header_display = $header_display ?? "d-block";
    $scripts = $scripts ?? "";
    $styles = $styles ?? "";
    $theme = $_SESSION['user_theme'] ?? "dark";
    $logo_style = ($theme == "dark") ? "DarkLogo" : "LightLogo";

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

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        
        <!-- JQuery  -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <!-- Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


        <!--Custom Stylesheets -->
        <link rel="stylesheet" href="public_html/css/default.css">
        <?= $styles ?>

        <!--Custom Javascript -->
        <script src="public_html/js/default.js"></script>
        
        <?= $scripts ?>

    </head>
    <body class="<?=$theme?>">
        <?php require_once 'resources\templates\toast.php'; ?>
        <header class="<?=$header_display?>">
            <nav>
                <div class="sidebar-header">Here</div>
            </nav>
        </header>
    
 <!-- ... Content  -->
<?php
 if(isset($_GET['error'])){
    
    ?><script type='text/JavaScript'>showError('<?=$_GET['error']?>');</script>`;<?php
    unset($_GET['error']);
}
?>