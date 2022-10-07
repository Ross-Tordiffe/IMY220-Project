<?php

    // Default header variables (if not set)
    $title = $title ?? "";
    $header_display = $header_display ?? "d-block";
    $styles = $styles ?? "";
    // $theme = $_SESSION['user_theme'] ?? "light";
    $theme = "light";
    $logo_style = ($theme === "dark") ? "DarkLogo" : "LightLogo";
    $icon_style = ($theme === "dark") ? "Icon-Dark" : "Icon-Light";
    $content_offset = $content_offset ?? "col offset-3 offset-xl-2 table-main"; 
    $hd_other = $hd_other ?? "";

    require_once 'resources/config.php';

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
        <?php require_once 'resources/templates/toast.php'; ?>
        <div class="container-fluid p-0 super-container">
            <div id="user-id" class="d-none"><?=$_SESSION["user_id"]?></div>
            <div class="row p-0 m-0">
                <header class="header col-xl-2 col-md-3 col-0 px-1 <?=$header_display?> sticky-sidebar"> <!-- Header -->
                    <div class="hd-logo">
                        <img src="public_html/img/page/<?=$logo_style?>.svg" alt="header logo" class="img-fluid">
                    </div>
                    <nav class="col hd-nav">
                        <h2 class="hd-nav-link">
                            <a href="home.php" class="nav-link">Home</a>
                        </h2>
                        <h2 class="hd-nav-link">
                            <a href="explore.php" class="nav-link">Explore</a>
                        </h2>
                        <h2 class="hd-nav-link">
                            <a href="profile.php?user_id=<?=$_SESSION["user_id"]?>" class="nav-link">Profile</a>
                        </h2>
                    </nav>
                    <div class="hd-other">
                        <?= $hd_other ?>
                    </div>
                    
                </header>
                <footer class="<?=$header_display?> hd-footer col-xl-2 col-md-3 col-0 px-1">
                    <div class="col-md-12 d-flex align-items-center justify-content-center">
                        <div class="row">
                            <div class="col-md-12 mb-md-0 pt-2 pb-3 text-center">
                                <!-- <small class="text-uppercase small-bigger">Links</small> -->
                                <div class="d-flex justify-content-around">
                                    <small class="small-bigger"><a href="home.php">Home</a></small>                                 
                                    <small class="small-bigger"><a href="games.php">Explore</a></small>  
                                    <small class="small-bigger"><a href="collections.php">Profile</a></small>  
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-center">
                                <i class="fa-solid fa-house"></i>
                            </div>
                            <div class="col-md-3 d-flex flex-column align-items-left p-0">
                                <ul class="list-unstyled">
                                    <li class="text-left"><small>782 Schoeman St</small></li>
                                    <li class="text-left"><small>Pretoria</small></li>
                                    <li class="text-left"><small>Gauteng</small></li>
                                    <li><small>0059</small></li>
                                </ul>
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                <div>
                                    <i class="fa-solid fa-mobile-screen-button"></i>
                                    <small><a href="tel:+0848969017">+084 896 9017</a></small>
                                </div>
                                <div>
                                    <i class="fa-solid fa-envelope"></i>
                                    <small><a href="mailto:" class="text-decoration-none">support@tabletap.com</a></small>
                                </div>
                            </div>
                            <div class="col-md-12 d-flex align-items-center justify-content-center pe-4">
                                <div class="footer-copyright text-center py-1"><small>© 2020 Copyright TableTap.com</small></div>
                            </div>
                        </div>
                    </div>
                    

                </footer>
                <div class="<?=$content_offset?> w-100 h-100 table-box position-relative">
                        <!-- ... Content  -->
                        <!-- Displays the centre table -->
                    <div class="row table-back h-100 w-100 position-absolute <?=$header_display?>">
                        <div class="col-md-12 p-0 d-flex justify-content-center h-100">
                            <div class="row justify-content-center table-outer w-100 h-100">
                                <div class="table-inner d-flex justify-content-center"></div>
                            </div>
                        </div>
                        <div class="table-bottom">
                            <div class="table-leg">
                                <div class="table-leg-highlight"></div>
                            </div>
                            
                            <div class="table-leg">
                                <div class="table-leg-highlight"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row table-runner position-absolute h-100 w-100 <?php echo($header_display)?>">
                        <div class="col-12 p-0 d-flex flex-column align-items-center h-100">
                            <div class="row table-runner-piece">
                                <div></div>
                            </div>
                            <div class="row align-items-center table-runner-piece h-100">
                                <div class="h-100 w-100"></div>
                            </div>
                        </div>
                    </div>
                    
<?php
    if(isset($_GET['error'])){
        ?><script type='text/JavaScript'>showError('<?=$_GET['error']?>');</script>`;<?php
        unset($_GET['error']);
    }
?>