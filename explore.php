<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    $styles = "<link rel='stylesheet' href='public_html/css/explore.css'>";

    $scripts = "
    <script src='public_html/js/explore.js'></script>";

    require_once("resources/templates/header.php");

?>

    <div class="row table-events position-absolute h-100 m-0">
        <div class="col-12 explore position-relative h-100">
            <div class="row search-bar-row no-search">
                <div class="col-12 explore-search-bar pt-3 ">
                    <div class="row justify-content-center h-100 align-items-start">
                        <div class="col-10">
                            <div class="explore-title">
                                <h1>Explore</h1>
                            </div>
                            <!-- uses jquery autocomplete -->
                            
                            <div class="input-group mb-3">
                                <input type="text" class="form-control explore-search" placeholder="e.g. Poker night, 12/10/2020, #ttrpg, @johnsmith" aria-label="Recipient's username" aria-describedby="button-addon2" autocomplete="on">
                                <button class="btn btn-primary explore-submit" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="row">
                <div class="col-12 explore-box-container">
                    <div class="col-12 explore-box d-flex flex-column w-100">
                        <div class="row explore-box-events event-box">
                            <div class="col-xl-4 col-lg-6 col-12 event-col-1 event-col"></div>
                            <div class="col-xl-4 col-lg-6 col-0 event-col-2 event-col"></div>
                            <div class="col-xl-4 col-lg-0 col-0 event-col-3 event-col"></div>
                        </div>
                        <div class="row explore-box-user-list mx-4">

                        </div>
                        <div class="no-results justify-content-center align-items-center">
                            <h2>No results found</h2>
                        </div>
                    </div>
                </div>
            </div>

<?php

    require_once("resources/templates/footer.php");

?>