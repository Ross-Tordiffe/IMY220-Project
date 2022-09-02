<?php 

    require_once("resources/config.php");

    $hd_other = '
        <div class="profile-groups card">
            <h3 class="card-header">Collections</h3>
            <ul class="overflow-auto list-group list-group-flush">
                <li class="list-group-item">Group 1</li>
                <li class="list-group-item">Group 2</li>
                <li class="list-group-item">Group 3</li>
                <li class="list-group-item">Group 4</li>
                <li class="list-group-item">Group 5</li>
            </ul>
        </div>';

    $styles = "<link rel='stylesheet' href='public_html/css/profile.css'>";

    $scripts = "
    <script src='public_html/js/profile.js'></script>
    <script src='public_html/js/eventHandle.js' defer></script>";

    require_once("resources/templates/header.php");

    if($_SESSION["logged_in"] == false){
        header("Location: index.php");
    }

?>

                            
                <div class="profile-container pt-3 h-100 position-absolute">
                    <h1 class="">Create event</h1>
                    <div class="row">
                        <div class="col-12 py3">
                            <div class="row w-100">
                                <div class="col-12 carousel carousel-card slide" data-ride="carousel">
                                    <div class="carousel-inner profile-card-container" role="listbox">
                                        <div class="profile-first first-item item active w-75" id="first-card">
                                            <form class="first-inputs p-2" autocomplete="off">
                                                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                <div class="first-inputs-inner d-flex flex-column justify-content-around p-3">
                                                    <div class="col form-group event-image-box d-flex justify-content-center w-100">
                                                        <input type="file" id="event-file" class="file-input" name="event-file" data-height="500" accept=".png, .jpg, .jpeg" />
                                                        <label for="event-file"></label>
                                                        <span class="event-image-btn"><img src="public_html/img/page/upload_icon.svg" class="event-image"/></span>
                                                    </div>
                                                    <div class="form-group pt-4">
                                                        <input type="text" id="event-title" class="form-control input-alt" name="event-title" placeholder=" " autocomplete="off"/>
                                                        <label for="event-file" class="h5">Add a name for your event</label>
                                                    </div>
                                                    <div class="row form-group event-loc-site d-flex align-items-center">
                                                        <div class="col-5 py-0">
                                                            <button id="event-location" class="btn event-btn d-flex align-items-center" name="event-location">
                                                                <i class="fa-solid fa-location-dot pe-1"></i><span class="h6 mb-0">Add location</span>
                                                            </button>
                                                        </div>
                                                        <div class="col-2 ps-3 event-or">
                                                            <span class="h5">or</span>
                                                        </div>
                                                        <div class="col-5 ps-0">
                                                            <input type="text" id="event-website" class="form-control input-alt" name="event-website" placeholder=" " autocomplete="off"/>
                                                            <label for="event-file" class="h5">Link a website</label>
                                                        </div>
                                                    </div>
                                                    <div class="row input-group date event-date-cat-box pt-3">
                                                        <div class="col-6 d-flex align-items-center">
                                                            <span class=" pe-2 pt-1">
                                                                <i class="event-icon fa-solid fa-calendar-day cal-icon"></i>
                                                            </span>
                                                            <input type="date" class="form-control h-100" name="event-date" id="date"/>
                                                        </div>
                                                        <div class="col-6 d-flex align-items-center">
                                                            <i class="event-icon fa-solid fa-book pe-2"></i>
                                                            <button id="event-location" class="btn event-btn d-flex align-items-center d-flex" name="event-location"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <span class="h6 mb-0"><i class="fa-solid fa-caret-down pe-2"></i>Select a category</span>
                                                            </button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <a class="dropdown-item" href="#">Traditional</a>
                                                                <a class="dropdown-item" href="#">TTRPG</a>
                                                                <a class="dropdown-item" href="#">Cards</a>
                                                                <a class="dropdown-item" href="#">Boardgame</a>
                                                                <a class="dropdown-item" href="#">Digital</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                   
                                                </div>  
                                            </form>
                                            <!-- <div class="first-logo">
                                                <img src="public_html/img/page/LightLogo.svg" alt="logo" class="img-fluid first-logo m-3 mt-0 rounded mx-auto d-block">
                                            </div> -->
                                            <div class="first-btn d-flex justify-content-center p-5 pb-4">
                                                <button id="first-secondbmit" class="btn px-5">Continue</button>
                                            </div>
                                 
                                        </div>
                                        <div class="profile-second second-item item w-75" id="signup-card">
                                            <form class="second-inputs p-2" autocomplete="off">
                                                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                    <div class="second-inputs-inner d-flex flex-column justify-content-around p-5 pb-4">
                                                        <div class="form-group py-5">
                                                            <input type="text" class="form-control input-alt" id="event-description" placeholder=" " name="event-description" autocomplete="off">
                                                            <label for="event-description" class="">Type up a description...</label>
                                                        </div>
                                                        <div class="form-group pb-5">
                                                            <span class="event-icon">#</span>
                                                            <input type="text" class="form-control input-alt" id="event-tags" placeholder=" " name="event-tags" autocomplete="off">
                                                            <label for="event-tags"> Add hashtags</label>
                                                        </div>
                                                    </div>  
                                                </form>
                                                <div class="second-btn d-flex justify-content-center p-4">
                                                    <button id="second-secondbmit" class="btn px-5">Post Event</button>
                                                </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
<?php

    require_once("resources/templates/footer.php");

?>