<?php

    // Page setup variables
    $title = "- Welcome";
    $header_display = "d-none";
    $scripts = "<script src='public_html/js/splash.js' defer></script><script src='public_html/js/vd-login.js' defer></script>";
    $styles = "<link rel='stylesheet' href='public_html/css/splash.css'>";

    require_once 'resources/templates/header.php';
?>

        <main>
            <div class="container-fluid">
                <div class="splash-container" class="position-relative">
                    <div class="row splash-table position-absolute">
                        <div class="col-md-12 d-flex justify-content-center">
                            <div class="row justify-content-center splash-table-outer ">
                                <div class="splash-table-inner d-flex justify-content-center"></div>
                            </div>
                        </div>
                        <div class="splash-table-bottom">
                            <div class="splash-table-leg">
                                <div class="splash-table-leg-highlight"></div>
                            </div>
                            
                            <div class="splash-table-leg">
                                <div class="splash-table-leg-highlight"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row splash-table-runner position-absolute">
                        <div class="col-12 d-flex flex-column align-items-center">
                            <div class="row splash-table-runner-piece">
                                <div></div>
                            </div>
                            <div class="row align-items-center splash-table-runner-piece h-100">
                                <div class="col position-relative d-flex justify-content-center">
                                    <div class="splash-table-mat position-absolute d-flex justify-content-center" >
                                        <img src="public_html/img/page/<?=$logo_style?>.svg" alt="logo" class="img-fluid splash-logo">
                                    </div>
                                </div>
                                <div class="col splash-text d-flex flex-column justify-content-center">
                                    <p class="h1">Adventure Awaits!</p>
                                    <p class="text-std">Keep track of all of your favourite table top games!</p>
                                    <p class="text-std">TableTap lets you create sessions for you and your friends, join a vast ocean of new games, from niche to notirious and meet an awesome diverse community of fellow table top enjoyers.</p>
                                    <div class="splash-lgsu-btn">
                                        <button class="btn splash-btn-lg" onClick="goToLogin(false);">Login</button>
                                        <button class="btn splash-btn-su" onClick="goToLogin(true);">Sign up</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row splash-table-runner-piece mb-5">
                                <div></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="splash-container pt-3">
                    <div class="row">
                        <div class="col-12 d-flex flex-column align-items-center py3">
                            <div class="row lgsu-runner">
                                <div class="col-12 carousel carousel-card slide" data-ride="carousel">
                                    <div class="carousel-inner lgsu-container" role="listbox">
                                        <div class="lg-item item active">
                                            <form action="resources/validation/vd-login.php" method="post" class="splash-lg p-2" autocomplete="off">
                                                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                <div class="lg-inputs d-flex flex-column justify-content-around p-5">
                                                    <h1 class="h1 p-4 pt-0">Login</h1>
                                                    <div class="form-group py-3">
                                                        <input type="email" class="form-control" id="lg-email" aria-describedby="emailHelp" placeholder=" " name="lg-email" autocomplete="off">
                                                        <label for="email">Email address</label>
                                                    </div>
                                                    <div class="form-group">
                                                        <input type="password" class="form-control" id="lg-password" placeholder=" " name="lg-password" autocomplete="off">
                                                        <label for="password">Password</label>
                                                    </div>
                                                </div>  
                                                <div class="lg-btn d-flex justify-content-center p-5 pb-4">
                                                    <button type="submit" class="btn px-5">Continue</button>
                                                </div>
                                                <div class="lg-logo">
                                                    <img src="public_html/img/page/LightLogo.svg" alt="logo" class="img-fluid lg-logo m-3 mt-0 rounded mx-auto d-block">
                                                </div>
                                            </form>
                                        </div>
                                        <div class="su-item item">
                                            <form action="resources/validation/vd-signup.php" method="post" class="splash-su p-2" autocomplete="off">
                                                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                    <div class="su-inputs d-flex flex-column justify-content-around p-5 pb-4">
                                                        <h1 class="h1 p-4 pt-0">Signup</h1>
                                                        <div class="row form-group">
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" id="su-firstname" placeholder=" " name="su-firstname" autocomplete="off">
                                                                <label for="first-name">First name</label>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="text" class="form-control" id="su-lastname" placeholder=" " name="su-lastname" autocomplete="off">
                                                                <label for="last-name">Last name</label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="su-username" placeholder=" " name="su-username" autocomplete="off">
                                                            <label for="email">Username</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="email" class="form-control" id="su-email" aria-describedby="emailHelp" placeholder=" " name="su-email" autocomplete="off">
                                                            <label for="email">Email address</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="password" class="form-control" id="su-password" placeholder=" " name="su-password" autocomplete="off">
                                                            <label for="password">Password</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="password" class="form-control" id="su-password-confirm" placeholder=" " name="su-password-confirm" autocomplete="off">
                                                            <label for="password">Confirm password</label>
                                                        </div>
                                                    </div>  
                                                    <div class="su-btn d-flex justify-content-center p-4">
                                                        <button type="submit" class="btn px-5">Register</button>
                                                    </div>
                                                </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>


<?php
    require_once 'resources/templates/footer.php';

    if(isset($_GET['signup'])){
        ?><script type='text/JavaScript' defer>
            let item = (<?=$_GET['signup']?>) ? $('.su-item') : $('.lg-item');
            $(".page-footer")[0].scrollIntoView();
            if($(item).siblings(".item").hasClass("active")) {
                $(item).siblings(".item").removeClass("active");
                $(item).addClass("active");
            }
        </script>";<?php
        unset($_GET['signup']);
    }
?>