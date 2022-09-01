<?php
    // Default header variables (if not set)
    $footer_display = $footer_display ?? "d-block";

    $scripts = $scripts ?? "";
?>

            <footer class="page-footer font-small blue py-4 ">
                <div class="container-fluid text-md-left">
                    <div class="row">
                        <div class="col-md-2 mt-md-0 mt-3 d-flex align-items-center">
                            <img src="public_html/img/page/LightLogo.svg" alt="TableTap Logo" class="img-fluid">
                        </div>
                        <hr class="clearfix w-100 d-md-none pb-3">
                        <div class="col-md-5 mb-md-0 mb-3">
                            <h5 class="text-center text-uppercase">Contact</h5>
                            <div class="row">
                                <div class="col-md-6 d-flex align-items-center justify-content-center">
                                    <div class="row">
                                        <div class="col-md-2 d-flex align-items-center">
                                            <i class="fa-solid fa-house"></i>
                                        </div>
                                        <div class="col-md-10 d-flex flex-column align-items-left">
                                            <ul class="list-unstyled">
                                                <li class="text-left"><small>782 Schoeman St</small></li>
                                                <li class="text-left"><small>Pretoria</small></li>
                                                <li class="text-left"><small>Gauteng</small></li>
                                                <li><small>0059</small></li>
                                            </ul>
                                        </div>
                                    </div>
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
                            </div>
                                   
                        </div>
                        <div class="col-md-2 mb-md-0 mb-3 text-center">
                            <h5 class="text-uppercase">Links</h5>
                            <ul class="list-unstyled">
                                <li>
                                <a href="home.php">Home</a>
                                </li>
                                <li>
                                <a href="games.php">Games</a>
                                </li>
                                <li>
                                <a href="collections.php">Groups</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-3 d-flex align-items-center justify-content-center">
                            <div class="footer-copyright text-center py-3"><small>© 2020 Copyright TableTap.com</small></div>
                        </div>
                    </div>
                </div>
                
            </footer>
        <!-- JQuery  -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        
        <!--Custom Javascript -->
        <script src="public_html/js/default.js"></script>
        <?= $scripts ?>
    </body>
</html>