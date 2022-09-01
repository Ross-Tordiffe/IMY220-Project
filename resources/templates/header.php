<?php

    // Default header variables (if not set)
    $title = $title ?? "";
    $header_display = $header_display ?? "d-block";
    $styles = $styles ?? "";
    $theme = $_SESSION['user_theme'] ?? "light";
    $logo_style = ($theme == "dark") ? "DarkLogo" : "LightLogo";
    $icon_style = ($theme == "dark") ? "Icon-Dark" : "Icon-Light";

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
        
        <link rel="icon" type="image/x-icon" href="public_html/img/page/<?=$icon_style?>.svg">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!--Custom Stylesheets -->
        <link rel="stylesheet" href="public_html/css/default.css">
        <?= $styles ?>      

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