$(() => {

    // fillDummyData();

    const urlParams = new URLSearchParams(window.location.search);
    const event_id = urlParams.get("event_id");
 
    // === Handle swapping cards/forms ===

        const swapCards = (item) => {
            if($(item).siblings(".item").hasClass("active")) {
                $(item).siblings(".item").removeClass("active");
                $(item).addClass("active");
            }
        }
        
        $(".create-event-first").on("click", (e) => {
            if(!$(".first-item").hasClass("active")) {
                swapCards($(".first-item"));
            }
        });
        
        $(".create-event-second, #create-event-continue").on("click", (e) => {
            e.stopPropagation();
            if(!$(".second-item").hasClass("active")) {
                swapCards($(".second-item"));
            }
        });

    // === Handle inputs ===

    // --- Image upload drag and drop ---

    $(".event-image-box").on("dragenter, dragover", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-image-box").addClass("event-image-box-dragging");
    });

    $(".event-image-box").on("dragleave", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-image-box").removeClass("event-image-box-dragging");
    });
    
    $(".event-image-box").on("drop", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-image-box").removeClass("event-image-box-dragging");
        let file = e.originalEvent.dataTransfer.files;
        if(handleImageFile(file)){
            imageFile = file[0];
            console.log(imageFile);
        }
        ("success?");
    });

    $("#event-file").on("change", (e) => {
        let file = e.target.files;
        handleImageFile(file);
    });

    const handleImageFile = (file) => {

        // Check if the file is one of the allowed types (jpg, jpeg, png)
        if(file[0].type === "image/jpeg" || file[0].type === "image/png") {
            // Check if the file is less than 2MB
            if(file[0].size < 2000000) {
                let reader = new FileReader();
                reader.readAsDataURL(file[0]);
                reader.onload = (e) => {
                    $(".event-image-box").css("background-image", `url(${e.target.result})`);
                    $(".event-image-btn").addClass("event-image-hidden");
                }
                return true;
            } else {
                showError("File size is too large. Please upload a file less than 2MB.");
                return false;
            }
        } else {
            showError("File type not supported");
            return false;
        }
    
    }

    const uploadImage = (file) => {
        let formData = new FormData();
        formData.append("file", file[0]);
        formData.append("request", "uploadImage");
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: (data) => {
                console.log(data);
            },
            error: (err) => {
                console.log(err);
            }
        });
    }

    // --- Handle title
    
    $("#event-title").on("keyup", (e) => {
        $("#title-counter").text(`${e.target.value.length}/30`);
    });

    // --- Handle location

    $("#event-location").on("click", (e) => {
        e.preventDefault();
        
        // Loading gif while getting location
        $("#event-location span").css("display", "none");
        $("#event-location").append("<img src='public_html/img/page/loading_light.svg' class='loading-anim'>");
        $("#event-location").css("pointer-events", "none");

        getLocation().then((coords) => {
            $("#event-location span").css("display", "inline");
            $("#event-location img").remove(); 
            $(".map-box").css("display", "block");
            $("#event-location").css("pointer-events", "auto");

            $(".map-box").removeClass("d-none");
            
            setCoords(coords.lat, coords.lng);
        
        });
        
    });

    $("#map-btn").on("click", (e) => {
        e.preventDefault();

        $(".map-box").addClass("d-none");
        
        $("#event-location span").text(address);
        $(".event-or").addClass("d-none");
        $(".event-website").addClass("d-none");

        if($(".event-location").hasClass("d-none")) {
            $(".event-location").removeClass("d-none");
        }
        $(".event-location").removeClass("col-5");
        $(".event-location").addClass("col-11");
        $(".event-loc-site-cancel").addClass("col-1");
        $(".event-loc-site-cancel").addClass("grow-1");
        $("#event-location").addClass("w-100");
    });

    // --- Handle Website

    const expandWebsite = () => {
        $(".event-or").addClass("shrink");
     
            $(".event-location").addClass("shrink");
    
            $(".event-website").addClass("grow-11");
    
            $(".event-loc-site-cancel").addClass("col-1");
            $(".event-loc-site-cancel").addClass("grow-1");
            setTimeout(() => {
                $(".event-or").addClass("d-none");
                $(".event-location").addClass("d-none");
                $(".event-website").removeClass("col-5");
                $(".event-website").addClass("col-11");
            }, 500);
            if($(".event-website").hasClass("d-none")) {
                $(".event-website").removeClass("d-none");
            }
    }

    $("#event-website").on("keyup", (e) => {
        if(e.target.value.length > 0) {
            expandWebsite();
        }
    });

   

    $(".event-loc-site-cancel").on("click", (e) => {
        e.preventDefault();
        resetLocSite();
    });

    const resetLocSite = () => {
        if($(".event-or").hasClass("shrink")) {

            $(".event-or").removeClass("d-none");
            $(".event-location").removeClass("d-none");
            
            $(".event-or").removeClass("shrink");
            $(".event-location").removeClass("shrink");

            $(".event-website").removeClass("grow-11");
            $(".event-website").addClass("col-5");
            $(".event-website").removeClass("col-11");
            $(".event-loc-site-cancel").removeClass("col-1");
            $(".event-loc-site-cancel").removeClass("grow-1");
            $("#event-website").val("");
        }
        else if ($(".event-or").hasClass("d-none")) {
            $(".event-or").removeClass("d-none");
            $(".event-location").removeClass("d-none");
            
            $("#event-location span").text("Add location");
            $(".event-or").removeClass("d-none");
            $(".event-website").removeClass("d-none");
    
            $(".event-location").addClass("col-5");
            $(".event-location").removeClass("col-11");
            $("#event-location").removeClass("w-100");

            $(".event-loc-site-cancel").removeClass("col-1");
            $(".event-loc-site-cancel").removeClass("grow-1");
        }
    } 

    // --- Handle category

    $(".dropdown-item").on("click", (e) => {
        console.log(e.target.firstChild.data);
        $("#event-category>label>span").text(e.target.firstChild.data);
    });

    // --- Handle description

    $("#event-description").on("keyup", (e) => {
        $("#description-counter").text(`${e.target.value.length}/240`);
    });


    // --- Handle hashtags

    $("#event-add-tag").on("click", (e) => {
        if($(".tag-container").children().length < 20) {
            let tags = $("#event-tags").val().replace(/#/g, " ").trim().split(/\s+/);
            // Add hashtags to each item in the list
            tags.forEach((tag) => {
                //check if tag is already added
                if($(".tag-container").children().length < 20 && !$(".tag-container").children().text().includes(tag)) {
                    $(".tag-container").append(
                        $(`<div></div>`, {
                            html: `#${tag.toLowerCase()}`,
                            class: `badge tag-item`,
                        })
                    );
                };
            });
            $("#event-tags").val("");
            $("#tag-counter").text(`${$(".tag-container").children().length}/20`);
        }
    });

    // Remove tag when clicked
    $(".tag-container").on("click", ".tag-item", (e) => {
        e.target.remove();
    });

    // === Handle form submission ===

    $("#create-event-submit").on("click", (e) => {
        e.preventDefault();
        e.stopPropagation();

        // Check all necessary fields are filled
        let message = checkFields();
        if(message.length > 0) {
            // Display error message
            showError(message);
        }
        else {  // Form submission
            
            // Get form data
            let formData = new FormData($(".first-inputs")[0]);
            formData.append("event-location", $("#event-location span").text());
            formData.append("event-category", $("#event-category>label>span").text());
            formData.append("event-description", $("#event-description").val());

            // Create space separate a list of hashtags
            let tags = "";
            $(".tag-container").children().each((i, tag) => {
                //add space if not last
                if(i < $(".tag-container").children().length - 1) {
                    tags += `${tag.innerText} `;
                }
                else {
                    tags += `${tag.innerText}`;
                }
            });
            formData.append("event-tags", tags);


            // Check if imageFile is set and append it to formData
            if(imageFile) {
                formData.append("event-file", imageFile);
            }

            // convert formdata into json
            let json = {};
            for (let [key, value] of formData.entries()) {
                json[key] = value;
            }

            // If the user is creating an event
            if(!event_id) {

                // Send form data to server
                formData.append("request", "createEvent");

                $.ajax({
                    url: "requests.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (data) => {
                        console.log(data);
                        data = JSON.parse(data);
                        if(data.status == "success") {
                            window.location.href = "home.php";
                        }
                        else {
                            let message = "error: " + data.message;
                            showError(message);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                        showError("Something went wrong. Please try again.");
                    }
                });

            }
            // If the user is editing an event
            else {

                // Send form data to server
                formData.append("request", "updateEvent");
                formData.append("event-id", event_id);

                $.ajax({
                    url: "requests.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (data) => {
                        data = JSON.parse(data);
                        if(data.status == "success") {
                            window.location.href = "event.php?id=" + event_id;
                        }
                        else {
                            let message = "error: " + data.message;
                            showError(message);
                        }
                    },
                    error: (err) => {
                        console.log(err);
                        showError("Something went wrong. Please try again.");
                    }
                });
            }
           
        }
       
    });

    // === Handle form validation ===

    const checkFields = () => {
        if(!imageCheck()) { // No image uploaded
            return "Please upload an image";
        } if(!titleCheck()) { // No title entered
            return "Please enter a title";
        } if (!locationCheck() && !websiteCheck()) { // No location or website entered or invalid website
            if($("#event-website").val().length > 0) { // Invalid url entered
                return "Please enter a valid website";
            }
            else { // No location or website entered
                return "Please specify a location or website";
            }
        } if (!dateCheck()) { // No date entered or invalid date
            if($("#event-date").val().length > 0) { // Invalid date entered
                return "Please enter a valid date";
            }
            else { // No date entered
                return "Please enter a date";
            }
        } if (!categoryCheck()) { // No category selected
            return "Please enter a category";
        }

        return "";
    }

    const imageCheck = () => {
        if($(".event-image-box").css("background-image") === "none") {
            return false;
        } else {
            return true;
        }
    };

    const titleCheck = () => {
        if($("#event-title").val() === "") {
            
            return false;
        } else {
            return true;
        }
    }

    const locationCheck = () => {
        if($("#event-location span").text() === "Add location") {
            return false;
        } else {
            return true;
        }
    }

    const websiteCheck = () => {
        let urlValitation = /[(http(s)?):\/\/(www\.)?a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)/ig;
        if($("#event-website").val() === "" || !urlValitation.test($("#event-website").val())) {
            return false;
        } else {
            return true;
        }
    }

    
    const dateCheck = () => {
        if($("#event-date").val() === "" || new Date($("#event-date").val()) < new Date()) {
            return false;
        } else {
            return true;
        }
    }

    const categoryCheck = () => {
        if($("#event-category>label>span").text() === "Select a category") {
            return false;
        } else {
            return true;
        }
    }

    // If and event_id is present in the url, the user is editing an event and the form should be populated with the event's data
    if(event_id) {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "getEvent",
                event_id: event_id
            },
            success: (data) => {
                data = JSON.parse(data);
                if(data.status == "success") {

                    // Change form title
                    $(".create-event-title").text("Edit Event");
                    
                    data = data.data;

                    // Populate form with event data
                    $("#event-title").val(data.event_title);

                    $("#title-counter").text(data.event_title.length + "/30");

                    $("#event-date").val(data.event_date);
                    
                    // Either location or website will be set, not both
                    if(data.event_location) {
                        $("#event-location span").text(data.event_location);
                        $(".event-or").addClass("d-none");
                        $(".event-website").addClass("d-none");
                
                        if($(".event-location").hasClass("d-none")) {
                            $(".event-location").removeClass("d-none");
                        }
                        $(".event-location").removeClass("col-5");
                        $(".event-location").addClass("col-11");
                        $(".event-loc-site-cancel").addClass("col-1");
                        $(".event-loc-site-cancel").addClass("grow-1");
                        $("#event-location").addClass("w-100");
                    }
                    else {
                        $("#event-website").val(data.event_website);
                        expandWebsite();
                    }

                    $("#event-category>label>span").text(data.event_category);
                    $("#event-description").val(data.event_description);
                    $("#description-counter").text(data.event_description.length + "/500");
                    
                    // Add tags to tag container from tags json array
                    data.event_tags.forEach((tag) => {
                        $(".tag-container").append(`<div class='badge tag-item'>${tag}</div>`);
                    });

                    // Set image
                    $(".event-image-box").css("background-image", `url("public_html/img/event/${data.event_image}")`);
                    $(".event-image-btn").addClass("event-image-hidden");

                    $("#create-event-submit").text("Save Event");
                }
                    
                else {
                    showError(data.message);
                }
            },
            error: (err) => {
                console.log(err);
                showError("Something went wrong. Please try again.");
            }
        });
    }
});  

// === Handle map (Needs to be defined before google script is loaded) ===

var map;
var marker;
var coords = {lat: -25.731340, lng: 28.218370};
var address = "";
var imageFile = null;

/**
 * Gets the coordinates of the user's current location if possible
 * @returns {void}
 */
const getLocation = async () => {
    const location = new Promise((resolve, reject) => {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const location = {lat, lng};
                resolve(location);
            }, () => {
                resolve(coords);
            });
        } else if(location !== undefined) {
            resolve(location);
        } else {
            resolve(coords);
        }
    });
    return location;
};

/**
 * Initializes the google map
 */
function initMap() {

    const map = new google.maps.Map(document.getElementById("map"), {
        center: coords,
        zoom: 15,
        streetViewControl: false,
    });

    const marker = new google.maps.Marker({
        position: coords,
        map: map,
        draggable: true,
        title: "Drag me!",
    });

    const geocoder = new google.maps.Geocoder();
    const infowindow = new google.maps.InfoWindow();

    // document.getElementById("submit").addEventListener("click", () => {
    //     geocodeLatLng(geocoder, map, infowindow);
    // });

    geocodeLatLng(geocoder, map, infowindow, marker)

    map.addListener("click", (e) => {
        coords = {lat: e.latLng.lat(), lng: e.latLng.lng()};
        geocodeLatLng(geocoder, map, infowindow, marker)
    });

};

/**
 * Sets the marker coordinates
 * @param {*} lat 
 * @param {*} lng 
 */
const setCoords = (lat, lng) => {
    coords = {lat, lng};
    initMap();
}

/**\
 * Geocodes the coordinates of the marker and displays the address in the input field
 */
function geocodeLatLng(geocoder, map, infowindow, marker) {
    const latlng = {
      lat: parseFloat(coords.lat),
      lng: parseFloat(coords.lng),
    };
  
    geocoder
      .geocode({ location: latlng })
      .then((response) => {
        if (response.results[0]) {
          map.setZoom(15);
  
        marker.setPosition(latlng);
        marker.setMap(map);
  
          address = response.results[0].formatted_address
          infowindow.setContent(response.results[0].formatted_address);
          infowindow.open(map, marker);
        } else {
          window.alert("No results found");
        }
      })
      .catch((e) => window.alert("Geocoder failed due to: " + e));
  }
  

window.initMap = initMap;

/**
 * Fills the form with dummy data for testing purposes
 */
const fillDummyData = () => {
    $("#event-title").val("Test Event");
    $("#event-location>span").text("London, UK");
    $("#event-website").val("https://www.google.com");
    $("#event-date").val("2022-10-10");
    $("#event-category>label>span").text("Cards");
    $("#event-description").val("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla vel lectus euismod, ultricies nisl vitae, rhoncus nisl. Morbi id tellus id mauris lacinia ultricies.");
    for(let i = 0; i < 5; i++) {
        $(".tag-container").append(`<div class='badge tag-item'>#test${i}</div>`);
    }
};

