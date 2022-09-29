<?php 

    require_once("resources/config.php");

    if($_SESSION["logged_in"] === false){
        header("Location: index.php");
    }

    // $hd_other = '
    //     <div class="event-groups card">
    //         <h3 class="card-header">Collections</h3>
    //         <ul class="overflow-auto list-group list-group-flush">
    //             <li class="list-group-item">Group 1</li>
    //             <li class="list-group-item">Group 2</li>
    //             <li class="list-group-item">Group 3</li>
    //             <li class="list-group-item">Group 4</li>
    //             <li class="list-group-item">Group 5</li>
    //         </ul>
    //     </div>';

    $styles = "<link rel='stylesheet' href='public_html/css/create-event.css'>";

    $scripts = "
        <script src='public_html/js/createEvent.js'></script>
        <script src='public_html/js/eventHandle.js' defer></script>
        <script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDQLaPWKg1VN8PmDCSNuyMgu0DCc1QY8NA&callback=initMap&v=weekly' defer></script>
    ";

    require_once("resources/templates/header.php");

?>                          
    <!-- Create Event -->
                <!-- Full container -->
                <div class="create-event-container pt-3 h-100 position-absolute">
                    <!-- Title -->
                    <h1 class="h1 create-event-title">Create event</h1>
                    <!-- Create Event forms -->
                    <div class="row">
                        <!-- Card swap containers -->
                        <div class="col-12 py3">
                            <div class="row w-100">
                                <div class="col-12 carousel carousel-card slide" data-ride="carousel">
                                    <div class="carousel-inner create-event-card-container" role="listbox">
                                        <!-- First card -->
                                        <div class="create-event-first first-item item active w-75" id="first-card">
                                            <form class="first-inputs p-2" autocomplete="off">
                                                <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                <!-- Inner inputs section -->
                                                <div class="first-inputs-inner d-flex flex-column justify-content-around p-3">
                                                    <!-- Image upload -->
                                                    <div class="col form-group event-image-box d-flex justify-content-center w-100">
                                                        <input type="file" id="event-file" class="file-input" name="event-file" data-height="500" accept=".png, .jpg, .jpeg" />
                                                        <label for="event-file"></label>
                                                        <span class="event-image-btn">
                                                            <svg class="event-image-icon" width="119" height="95" viewBox="0 0 119 95" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M71.2576 23.1134V24.2224H47.8277V0.79248H48.9368C50.1018 0.792487 51.2192 1.2553 52.0431 2.07911L69.9708 20.0069C70.7947 20.8308 71.2576 21.9482 71.2576 23.1134V23.1134ZM46.3633 30.0799C43.9471 30.0799 41.9702 28.103 41.9702 25.6868V0.79248H5.36089C2.93461 0.79248 0.967773 2.75932 0.967773 5.1856V90.1192C0.967773 92.5455 2.93461 94.5123 5.36089 94.5123H66.8645C69.2908 94.5123 71.2576 92.5455 71.2576 90.1192V30.0799H46.3633ZM21.5687 33.0087C26.4213 33.0087 30.355 36.9423 30.355 41.7949C30.355 46.6475 26.4213 50.5811 21.5687 50.5811C16.7162 50.5811 12.7825 46.6475 12.7825 41.7949C12.7825 36.9423 16.7164 33.0087 21.5687 33.0087ZM59.6424 76.9398H12.7825L12.8713 68.0648L20.1044 60.8317C20.9621 59.974 22.2641 60.0628 23.1219 60.9205L30.355 68.1536L49.303 49.2055C50.1608 48.3478 51.5516 48.3478 52.4095 49.2055L59.6424 56.4386V76.9398V76.9398Z" fill="#FDF0D5"/>
                                                                <path d="M115.892 52.5141H102.712V39.3347C102.712 37.7175 101.401 36.406 99.7837 36.406H96.855C95.2377 36.406 93.9262 37.7175 93.9262 39.3347V52.5141H80.7469C79.1296 52.5141 77.8181 53.8256 77.8181 55.4428V58.3716C77.8181 59.9888 79.1296 61.3003 80.7469 61.3003H93.9262V74.4797C93.9262 76.0969 95.2377 77.4084 96.855 77.4084H99.7837C101.401 77.4084 102.712 76.0969 102.712 74.4797V61.3003H115.892C117.509 61.3003 118.821 59.9888 118.821 58.3716V55.4428C118.821 53.8256 117.509 52.5141 115.892 52.5141Z" fill="#FDF0D5"/>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <!-- Event name -->
                                                    <div class="form-group pt-5">
                                                        <div class="has-counter">
                                                            <input type="text" id="event-title" class="form-control input-alt" name="event-title" placeholder=" " autocomplete="off" maxlength="30"/>
                                                            <label for="event-file" class="h5">Add a name for your event</label>
                                                            <div class="character-counter" id="title-counter">0/30</div>
                                                        </div>
                                                        
                                                       
                                                    </div>
                                                    <!-- Event location or site -->
                                                    <div class="row form-group event-loc-site d-flex align-items-center flex-nowrap">
                                                        <div class="col-5 py-0 event-location">
                                                            <button id="event-location" class="btn event-btn d-flex align-items-center" name="event-location">
                                                                <i class="fa-solid fa-location-dot pe-1"></i><span class="h6 mb-0">Add location</span>
                                                            </button>
                                                        </div>
                                                        <div class="col-2 event-or">
                                                            <span class="h5">or</span>
                                                        </div>
                                                        <div class="col-5 ps-0 event-website">
                                                            <input type="text" id="event-website" class="form-control input-alt" name="event-website" placeholder=" " autocomplete="off" maxlength="512"/>
                                                            <label for="event-website" class="h5">Link a website</label>
                                                        </div>
                                                        <div class="col-0 event-loc-site-cancel ">
                                                            <i class="fa-solid fa-x"></i>
                                                        </div>
                                                    </div>
                                                    <!-- Event and category -->
                                                    <div class="row input-group date event-date-cat-box pt-3">
                                                        <!-- Event date -->
                                                        <div class="col-6 d-flex align-items-center">
                                                            <span class=" pe-2 pt-1">
                                                                <i class="event-icon fa-solid fa-calendar-day cal-icon"></i>
                                                            </span>
                                                            <input type="date" class="form-control h-100" name="event-date" id="event-date"/>
                                                        </div>
                                                        <!-- Event category -->
                                                        <div class="col-6 d-flex align-items-center">
                                                            <i class="event-icon fa-solid fa-book pe-2"></i>
                                                            <button id="event-category" class="btn event-btn d-flex" name="event-category" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <label for="category-dropdown" class="h6 mb-0"><i class="fa-solid fa-caret-down pe-2"></i><span>Select a category</span></label>
                                                            </button>
                                                            <div class="dropdown-menu" id="category-dropdown" aria-labelledby="dropdownMenuButton">
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
                                            <!-- Contine button -->
                                            <div class="first-btn d-flex justify-content-center p-5 pb-4">
                                                <button id="create-event-continue" class="btn px-5">Continue</button>
                                            </div>
                                 
                                        </div>
                                        <!-- Second card -->
                                        <div class="create-event-second second-item item w-75">
                                            <form class="second-inputs p-2" autocomplete="off">
                                                    <!-- Inner inputs section -->
                                                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                                                    <div class="second-inputs-inner d-flex flex-column p-5 pb-4">

                                                        <!-- Event description -->
                                                        <div class="form-group py-5 has-counter">
                                                            <label for="event-description" class="">Type up a description...</label>
                                                            <textarea type="text" class="form-control input-alt" id="event-description" placeholder=" " name="event-description" rows=3 maxlength="240"></textarea>
                                                            <div class="character-counter" id="description-counter">0/240</div>
                                                        </div>
                                                        <!-- Event hash tags -->
                                                        <div class="row event-tag-box">
                                                            <span class="col-1 event-icon">#</span>
                                                            <div class="col-10 form-group has-counter">
                                                                <input type="text" class="form-control input-alt" id="event-tags" placeholder=" " name="event-tags" autocomplete="off" maxlength="50">
                                                                <label for="event-tags">Add hashtags</label>
                                                                <div class="character-counter me-2" id="tag-counter">0/50</div>
                                                            </div>
                                                            <div class="col-1 event-icon"><span id="event-add-tag" class="icon-clickable">+</span></div>
                                                            <div class="tag-container d-flex flex-wrap">
                                                            </div>
                                                        </div>
                                                    </div>  
                                                </form>
                                                <!-- Post Event button -->
                                                <div class="second-btn d-flex justify-content-center p-4">
                                                    <button id="create-event-submit" class="btn px-5">Post Event</button>
                                                </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Google Map popup -->
                    <div class="map-box d-none card position-absolute">
                        <div class="card-body d-flex flex-column h-100 p-3">
                            <div class="h3 font-weight-bold" id="map-title">Select a location</div>
                            <div class="h-100" id="map"></div>
                            <button class="btn btn-primary mt-2 w-25 align-self-center" id="map-btn">Save</button>
                        </div>
                    </div>
                </div>
            </div>
           
<?php

    require_once("resources/templates/footer.php");

?>