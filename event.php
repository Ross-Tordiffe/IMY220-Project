<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $styles = "<link rel='stylesheet' href='public_html/css/event.css'>";

    $scripts = "
    <script src='public_html/js/event.js'></script>";

    require_once("resources/templates/header.php");

?>

    <!-- Full container -->
    <div class="row table-events">
        <div class="col-12">
            <div class="row event-container justify-content-center"></div>
            <!-- images carousel -->
            <div class="row py-5 m-0">
                <div class="col-12 event-carousel px-0 d-none">
                    
                    <div id="event-images-carousel" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#event-images-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        </div>
                        <div class="d-flex justify-content-center">
                           
                            <button class="carousel-control-prev" type="button" data-bs-target="#event-images-carousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                <!-- <img src="..." class="d-block w-100" alt="..."> -->
                                    <div class="image-template d-flex"></div>
                                        <i class="fas fa-image fs-1"></i></div>
                                <div class="carousel-caption d-none d-md-block">
                                    <h5>No images</h5>
                                    <p>Submit a review to add an image</p>
                                </div>
                                </div>
                            </div>
                            <button class="carousel-control-next" type="button" data-bs-target="#event-images-carousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     

<?php

    require_once("resources/templates/footer.php");
    require_once("resources/templates/modals.php");

?>