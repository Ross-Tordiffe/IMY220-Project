// === Global Variables ===

var imageFile = null;

const EventPageObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => {

    if(event_location.length > 30) {
        event_location = event_location.substring(0, 30) + '...';
    }

    return (`
        <div class="row event-view">
            <div class="col-12 event-info">
                <div class="event-page-user-id d-none">${event_user_id}</div>
                <div class="event-page-id d-none">${event_id}</div>
                <div class="event-user-attending d-none"></div>
                <div class="row event-page-back p-2">
                    <div class="col-12 col-md-6 event-page-content p-0 position-relative">
                        <div class="p-2">
                    
                            <img class="img-fluid p-0" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                            <div class="event-page-details d-flex flex-column p-1 pt-1">
                                <div class="event-page-title">
                                    <span class="h2">${event_title}</span>
                                </div>
                                <div class="d-flex">
                                    <div class="event-page-rating-stars"></div>
                                    <div class="event-page-user-count">
                                        <span class="small"><i class="fas fa-users ps-3 pe-2"></i>${event_user_count}</span>
                                    </div>
                                </div>
                               
                                <div class="d-flex">
                                    <div class="event-page-date">
                                        <span class="small"><i class="fas fa-calendar-alt pe-2"></i>${event_date}</span>
                                    </div>
                                    <div class="event-page-location">
                                        <span class="small"><i class="fas fa-map-marker-alt ps-3 pe-2"></i>${event_location}</span>
                                    </div>
                                 
                                </div>
                            
                                <div class="event-page-description pt-2">
                                    <p class="small">${event_description}</p>
                                </div>
                                <div class="event-page-category">
                                    <span class="fw-bold">${event_category}</span>
                                </div>
                                <div class="event-page-hashtags">
                                    <div class="hashtag-container d-flex flex-wrap">
                                        ${
                                            event_tags.map((tag) => {
                                                return `<span class="event-hashtag small badge">${tag}</span>`
                                            }).join('')
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 event-page-reviews p-0">
                        <div class="row event-page-user m-0 h-100 position-relative">
                            <div class="col-12 event-page-user-info p-2 border-start-0 d-flex align-items-center justify-content-center position-absolute top-0">
                                <div class="col-2 col-md-2 event-page-user-image ms-3">
                                    <img class="img-fluid" src="public_html/img/user/${event_user_image}" alt="${event_user_image}"/>
                                </div>
                                <div class="col-12 col-md-10">
                                    <div class="event-page-user-name">
                                        <h3>${event_user_name}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 event-page-review-box d-inline-block h-100 position-absolute">
                                <div class="event-page-review-window p-2 w-100 h-100"></div>
                            </div>
                            <div class="col-12 event-page-add-review p-2 flex-shrink-1 d-flex justify-content-center align-items-center position-absolute bottom-0">
                                <button class="add-review-btn btn btn-primary btn-sm py-0">Attend<i class="fas ps-2"></i></button>
                                <button class="btn btn-primary btn-sm add-to-group py-0 ms-1"><i class="fas fa-plus"></i></button>
                            </div>
                            
                        </div>
                
                            
                    </div>
                </div>
            </div>
        </div>
    `)};

$(() => {

    // ======== Load Event Page =========

    // === Edit Event Functions ===
    
    getEvent.then((event) => {

        let event_user_id = $('.event-page-user-id').text();
        let user_id = $('#user-id').text();
        let event_id = $('.event-page-id').text();

        // if the user is the owner of the event then show the edit event button
        if (event_user_id == user_id) {
            $('.event-page-content').append(`
                <div class="col-12 event-page-edit position-absolute">
                    <button class="btn"><i class="fas fa-edit"></i></button>
                </div>
                <div class="col-12 event-page-delete position-absolute">
                    <button class="btn"><i class="fas fa-trash-alt"></i></button>
                </div>
            `);
        }

        // Edit icons
        $('.event-page-content').append(`
            <div class="col-12 event-page-edit-image edit-icon position-absolute">
                <button class="btn"><i class="fas fa-edit"></i></button>
            </div>
        `);

        $('.event-page-edit').click(() => {
            window.location.href = 'create-event.php?event_id=' + event.event_id;
        });

        $('.event-page-delete').click(() => {
            // Show confirmation modal
            $(".confirmation-modal-text p").text("Are you sure you want to delete this event?");
            $("#confirmationModal").modal("show");

            $("#confirmationModal").on("click", ".confirmation-modal-btn", (e) => {
                deleteEvent();
                $(".modal").modal("hide");
            });
        });

        isAttendingEvent(event_id);

        // if($("event-user-attending").text() == "true"){
        //     //disable attend button
        //     $(".add-review-btn").attr("disabled", true);
        // }
        // else {
        //     $(".add-review-btn").attr("disabled", false);
        // }

    });

    
  

    // === Event Page reviews ===

    getReviews.then((reviews) => {

        fillReviews(reviews);
        
    });

    // === Open review creation modal === 
    $(".event-container").on("click", ".add-review-btn", () => {
        console.log($(".event-user-attending").text());
        if($(".event-user-attending").text() == "false") {
            $("#eventReviewModal").modal("show");
        }
    });

    // === Handle review Stars ===

    var cur = $(".star-box");
    var curRating = parseInt($(".review-score").text() - 1);
    if($(".star-box").has('.star').length == 0){

        $(".star-box").append(`<div class="rating-box d-flex align-items-start">
            <div class="full-star star"><img src="public_html/img/page/star-empty.svg"/></div>
            <div class="half-star star"></div>
            <div class="full-star star"><img src="public_html/img/page/star-empty.svg"/></div>
            <div class="half-star star"></div>
            <div class="full-star star"><img src="public_html/img/page/star-empty.svg"/></div>
            <div class="half-star star"></div>
            <div class="full-star star"><img src="public_html/img/page/star-empty.svg"/></div>
            <div class="half-star star"></div>
            <div class="full-star star"><img src="public_html/img/page/star-empty.svg"/></div>
            <div class="half-star star"></div>
        </div>`)

    var inc = 15;
    $(".half-star").each((e) => {
        $(".half-star").eq(e).css("left", inc+"px")
        inc += 30;
    });

    }

    let wait = true;
    $(".event-review-form-container").on("mouseenter", ".star", (e) => {
            
        $(".star-box").find(".star").one('mouseenter', function() {
            fillStars(cur, $(this), 0);
            $($(this)).one('click', (() => {
                if(wait){
                    wait = false;
                    curRating = $(this).index();
                }
                setTimeout(() => {
                    wait = true;
                }, 200);
                
            }));
        });

    });

    $(".star-box").find(".rating-box").on('mouseleave', () => {
        fillStars(cur,  null, curRating);
    });

    $("#event-review-message").on("keyup", (e) => {
        $("#review-counter").text(`${e.target.value.length}/240`);
    });

    // === Handle image upload ===
    $(".event-review-image-box").on("dragenter, dragover", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-review-image-box").addClass("event-review-image-box-dragging");
    });

    $(".event-review-image-box").on("dragleave", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-review-image-box").removeClass("event-review-image-box-dragging");
    });
    
    $(".event-review-image-box").on("drop", (e) => {
        e.preventDefault();
        e.stopPropagation();
        $(".event-review-image-box").removeClass("event-review-image-box-dragging");
        let file = e.originalEvent.dataTransfer.files;
        if(handleImageFile(file)){
            imageFile = file[0];
        }
    });

    $("#event-review-file").on("change", (e) => {
        let file = e.target.files;
        if(handleImageFile(file)){
            imageFile = file[0];

        }
    });

    $(".event-review-image-box").on("click", (e) => {
        $("#event-review-file").click();
        // do not propegate to parent 
    });

    $(".review-submit").on("click", (e) => {
        e.preventDefault();
        
        let review_message = $("#event-review-message").val();
        
        createReview((curRating+1), review_message, imageFile);
        attendEvent($(".event-page-id").text());
    });

    // === Event Images Bootstrap Carousel ===

    $("event-images-carousel").carousel({
        interval: 5000
    });

    $(".carousel-control-prev").on("click", (e) => {
        e.preventDefault();
        $("#event-images-carousel").carousel("prev");
        // Make previous carousel item active

    });

    $(".carousel-control-next").on("click", (e) => {
        e.preventDefault();
        $("#event-images-carousel").carousel("next");
    });

    $(".carousel-indicators").on("click", (e) => {
        e.preventDefault();
        $("#event-images-carousel").carousel($(e.target).index());
    });

    $(".add-to-group").on("click", (e) => {
        e.preventDefault();
        addToGroup();
    });

    $(".event-container").on("click", ".add-to-group", (e) => {
        // Get the user's friends and return a promise
        getGroups();
        $("#showGroupsModal").modal("show");
    });

    $(".modal-body").on("click", ".event-group-list", (e) => {
        let group_id = $(e.target).children(".group_id").text();
        if(addEventToGroup(group_id));
            $("#showGroupsModal").modal("hide");
    });

});

// === Ajax Requests ===

const eventPage = new Promise((resolve, reject) => {

    const urlParams = new URLSearchParams(window.location.search);
    const event_id = urlParams.get('id');

    $.ajax({
        url: 'requests.php',
        type: 'POST',
        data: {
            request: 'getEvent',
            event_id: event_id
        },
        success: (data) => {
            const event = JSON.parse(data).data;
            console.log("AAAAAAAAAAAAAAAA", event.event_user_count);
            // Format event
            // Format the date from yyyy-mm-dd to dd/mm/yyyy
            let date = event.event_date.split("-");
            event.event_date = date[2] + "/" + date[1] + "/" + date[0];
            // Check entry for an empty location. if empty replace with event_website
            if(event.event_location === "") {
                // remove http:// or https://
                event.event_location = event.event_website.replace(/(^\w+:|^)\/\//, '');
            }
        
            // Format the user image
            $('.event-container').html(EventPageObject(event));
            resolve(event);
        },
        error: (err) => {
            console.log(err);
        }
    });
});

// === Promise Functions ===

const getEvent = eventPage.then(event);

const reviews = new Promise((resolve, reject) => {

    const urlParams = new URLSearchParams(window.location.search);
    const event_id = urlParams.get('id');

    $.ajax({
        url: 'requests.php',
        type: 'POST',
        data: {
            request: 'getReviews',
            event_id: event_id
        },
        success: (data) => {
            console.log(data);
            const reviews = JSON.parse(data).data;
            resolve(reviews);
        },
        error: (err) => {
            console.log(err);
        }
    });
});

const getReviews = reviews.then(reviews);

const isAttendingEvent = (event_id) => {
    const attending = new Promise((resolve, reject) => {
        
        let event_id = $('.event-page-id').text();

        console.log("EVENTID", event_id);
        
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "isAttendingEvent",
                event_id: event_id
            },
            success: (data) => {
                console.log(data);
                data = JSON.parse(data);
                
                if(data.status === "success") {
                    if(data.data == true) {
                        resolve(true);
                    }
                    else {
                        resolve(false);
                    }
                } else {
                    resolve(false);
                }
            }
        });
        
    }).then((data) => {
        console.log($(".event-container add-review-btn"));
        if(data){
            $(".event-container .add-review-btn").text("Attending");
            $(".event-user-attending").text("true");
        }
        else {
            $(".event-container .add-review-btn").text("Attend");
            $(".event-user-attending").text("false");
        }
        return data;
    });
};

// === Helper Functions ===

// --- Event Rating Stars ---

const fillStars = (cur, star, curRating) => {

    if(star != null)
        var limit = star.index();
    else {
        var limit = -1;
        $(".review-score").text(0 + ".0"); 
    }

    if(limit < curRating){
        limit = curRating;
    }
    var prev = null;
    $(cur).find(".star").each((i, item) => {
        if(i > limit){
            if((i % 2) == 0){
                // empty star
                $(item).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M287.9 0C297.1 0 305.5 5.25 309.5 13.52L378.1 154.8L531.4 177.5C540.4 178.8 547.8 185.1 550.7 193.7C553.5 202.4 551.2 211.9 544.8 218.2L433.6 328.4L459.9 483.9C461.4 492.9 457.7 502.1 450.2 507.4C442.8 512.7 432.1 513.4 424.9 509.1L287.9 435.9L150.1 509.1C142.9 513.4 133.1 512.7 125.6 507.4C118.2 502.1 114.5 492.9 115.1 483.9L142.2 328.4L31.11 218.2C24.65 211.9 22.36 202.4 25.2 193.7C28.03 185.1 35.5 178.8 44.49 177.5L197.7 154.8L266.3 13.52C270.4 5.249 278.7 0 287.9 0L287.9 0zM287.9 78.95L235.4 187.2C231.9 194.3 225.1 199.3 217.3 200.5L98.98 217.9L184.9 303C190.4 308.5 192.9 316.4 191.6 324.1L171.4 443.7L276.6 387.5C283.7 383.7 292.2 383.7 299.2 387.5L404.4 443.7L384.2 324.1C382.9 316.4 385.5 308.5 391 303L476.9 217.9L358.6 200.5C350.7 199.3 343.9 194.3 340.5 187.2L287.9 78.95z"/></svg>`);
            }
        }
        else{
            if((i % 2) == 0){
                // half star
                $(item).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M378.1 154.8L531.4 177.5C540.4 178.8 547.8 185.1 550.7 193.7C553.5 202.4 551.2 211.9 544.8 218.2L433.6 328.4L459.9 483.9C461.4 492.9 457.7 502.1 450.2 507.4C442.8 512.7 432.1 513.4 424.9 509.1L287.9 435.9L150.1 509.1C142.9 513.4 133.1 512.7 125.6 507.4C118.2 502.1 114.5 492.9 115.1 483.9L142.2 328.4L31.11 218.2C24.65 211.9 22.36 202.4 25.2 193.7C28.03 185.1 35.5 178.8 44.49 177.5L197.7 154.8L266.3 13.52C270.4 5.249 278.7 0 287.9 0C297.1 0 305.5 5.25 309.5 13.52L378.1 154.8zM287.1 384.7C291.9 384.7 295.7 385.6 299.2 387.5L404.4 443.7L384.2 324.1C382.9 316.4 385.5 308.5 391 303L476.9 217.9L358.6 200.5C350.7 199.3 343.9 194.3 340.5 187.2L287.1 79.09L287.1 384.7z"/></svg>`);
                $(".review-score").text(((i/2)+0.5)); 
            }
            else{
                // full star
                $(prev).html(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--! Font Awesome Pro 6.1.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M381.2 150.3L524.9 171.5C536.8 173.2 546.8 181.6 550.6 193.1C554.4 204.7 551.3 217.3 542.7 225.9L438.5 328.1L463.1 474.7C465.1 486.7 460.2 498.9 450.2 506C440.3 513.1 427.2 514 416.5 508.3L288.1 439.8L159.8 508.3C149 514 135.9 513.1 126 506C116.1 498.9 111.1 486.7 113.2 474.7L137.8 328.1L33.58 225.9C24.97 217.3 21.91 204.7 25.69 193.1C29.46 181.6 39.43 173.2 51.42 171.5L195 150.3L259.4 17.97C264.7 6.954 275.9-.0391 288.1-.0391C300.4-.0391 311.6 6.954 316.9 17.97L381.2 150.3z"/></svg>`);
                $(".review-score").text(((i/2)+0.5)+ ".0");
            }
        }
        prev = item;
        // "".star-box"" is the current element in the loop
    });
}

// --- Handle image validation --- //

const handleImageFile = (file) => {

    // Check if the file is one of the allowed types (jpg, jpeg, png)
    if(file[0].type === "image/jpeg" || file[0].type === "image/png") {
        // Check if the file is less than 2MB
        if(file[0].size < 2000000) {
            let reader = new FileReader();
            reader.readAsDataURL(file[0]);
            reader.onload = (e) => {
                $(".event-review-image-box").css("background-image", `url(${e.target.result})`);
                $(".event-review-image-i").addClass("event-review-icon-hide");
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

// --- Decide which message to show based on time ago --- //




// === Create a new review ===

const createReview = (rating, review_message, image) => {

    let message = review_message;

    if(rating <= 0) {
        showError("Please give a star rating");
        return false;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const event_id = urlParams.get('id');

    let formData = new FormData($("#event-review-form")[0]);
    formData.append("review_event_id", event_id);
    formData.append("review_user_id", $('#user-id').text());
    formData.append("review_rating", rating);
    formData.append("review_message", message);
    formData.append("review_image_file", image);
    formData.append("request", "createReview");

    $.ajax({
        url: "requests.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: (data) => {
            console.log(data);
            data = JSON.parse(data);
            if(data.status === "success") {
                // close modal
                $("#eventReviewModal").modal("hide");
                
                // get reviews again
                review = data.data;
                console.log("Review created successfully");
              
                getReviews.then((reviews) => {
                    // add review to the back of the reviews array
                    fillReviews(reviews);
                });

                // reload the page
                // window.location.reload();
                
            } else {
                showError(data);
            }
        }
    });

}

// Fill the event image carousel with the images

const fillEventImageCarousel = (reviews) => {
    let carousel = $(".carousel-inner");
    let carouselIndicators = $(".carousel-indicators");
    carouselIndicators.empty();

    console.log(reviews);
    
    carousel.empty();
    reviews.filter((review) => {
        return (review.review_image !== null && review.review_image !== "");
    }).forEach((review) => {

        let stars = '';
        let skip = (review.review_rating % 2 != 0) ? 1 : 0;

        // Fill stars with full stars up to the rating (include half stars)
        for (let i = skip; i < review.review_rating / 2; i++) {
            stars += '<i class="fas fa-star"></i>';
        }
        if (review.review_rating % 2 != 0) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }
        for (let i = 0; i < 5 - Math.ceil(review.review_rating / 2); i++) {
            stars += '<i class="far fa-star"></i>';
        }

        carousel.append(`
            <div class="carousel-item position-relative">
                <img src="public_html/img/review/${review.review_image}" class="d-block w-100 event-carousel-image" alt="...">
                <div class="carousel-caption d-none d-md-block">
                    <span class="h5">${stars}</span>
                    <p>${review.review_message}</p>
                </div>
                <div class="review-image-user position-absolute bottom-0 left-0 m-2 p-2 d-flex align-items-center">
                    <img src="public_html/img/user/${review.review_user_image}" class="event-carousel-user-image ms-2" alt="...">
                    <span class="h5 event-carousel-user-name px-2">${review.review_user_username}</span>
                </div>
            </div>
        `);

        carouselIndicators.append(`
            <button type="button" data-bs-target="#event-images-carousel" data-bs-slide-to="${carouselIndicators.children().length}" class="active" aria-current="true" aria-label="Slide ${carouselIndicators.children().length}"></button>
        `);

    });

    if(carousel.children().length > 0) {
        $(".event-carousel").removeClass("d-none");
    }

    $(".carousel-item").first().addClass("active");
    // set aria attributes
    $(".carousel-indicators button").each((index, item) => {
        $(item).attr("aria-label", `Slide ${index+1}`);
    });
}

const getGroups = () => {
    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "getGroups"
        },
        success: (data) => {
            data = JSON.parse(data);
            if(data.status === "success") {4
                data = data.data;
                // map the groups to <li class="event-group">Group Name</li>
                let groups = data.map((group) => { 
                    return `<li class="event-group-list fs-6 m-0"><div class="group_id d-none">${group.group_id}</div></div><i class="fas fa-border-all fs-4 me-2"></i> ${group.group_title}</li>`;
                });
                $(".event-show-groups-box").html(groups);
            } else {
                showError(data);
            }
        }
    });
}

const addToGroup = (event_id, user_id) => {
    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "addToGroup",
            event_id: event_id,
            user_id: user_id
        },
        success: (data) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                window.location.reload();
            } else {
                showError(data);
            }
        }
    });
}
    
const addEventToGroup = (group_id) => {

    let urlParams = new URLSearchParams(window.location.search);
    let event_id = urlParams.get('id');

    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "addEventToGroup",
            group_id: group_id,
            event_id: event_id
        },
        success: (data) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                return true;
            } else {
                showError(data);
            }
        }
    });
}

const deleteEvent = () => {

    let urlParams = new URLSearchParams(window.location.search);
    let event_id = urlParams.get('id');

    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "deleteEvent",
            event_id: event_id
        },
        success: (data) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                window.location.href = "index.php";
            } else {
                showError(data);
            }
        }
    });
}

const fillReviews = (reviews) => {

    // if there are no reviews then show the add review button

    let average_rating = 0;

    $('.event-page-review-window').empty();
    reviews.reverse();
        
    reviews.forEach((review) => {

        average_rating += review.review_rating;

        if(review.review_message != null && review.review_message != "") {

            console.log(review.review_message);

            let review_time_ago = timeAgo(review.review_time);
            let stars = '';
            let skip = (review.review_rating % 2 != 0) ? 1 : 0;

            // Fill stars with full stars up to the rating (include half stars)
            for (let i = skip; i < review.review_rating / 2; i++) {
                stars += '<i class="fas fa-star"></i>';
            }

        
            // If the rating is odd then add a half star
            if (review.review_rating % 2 != 0) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }

            // Fill the rest of the stars with empty stars
            for (let i = 0; i < 5 - Math.ceil(review.review_rating / 2); i++) {
                stars += '<i class="far fa-star"></i>';
            }

            $('.event-page-review-window').append(`
                <!-- Profile Image --><!-- Profile Name -->
                <!-- Star Rating -->
                <!-- Review Text -->
                <!-- Review Date -->
                <div class="row event-page-review px-2">
                    <div class="col-2 col-md-1 event-page-review-image">
                        <img class="img-fluid" src="public_html/img/user/${review.review_user_image}" alt="${review.review_user_image}"/>
                    </div>
                    <div class="col-10 col-md-6 event-page-review-name ms-2">
                        <h4 class="m-0">${review.review_user_username}</h4>
                    </div>
                    <div class="col-10 col-md-11 event-page-review-info">
                        <div class="row ">
                            <div class="col-12 event-page-review-rating pb-2 ps-2">
                                <span class="small">${stars}</span>
                            </div>
                        </div>
                        <div class="row ps-2">
                            <div class="col-12 event-page-review-text px-2 py-1">
                                <p class="small m-0">${review.review_message}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 event-page-review-date align-text-top">
                                <small>${review_time_ago}</small>
                            </div>
                        </div>
                    </div>
                </div>

            `);
        }
    });

    let stars = '';
    
    if(reviews.length > 0) {
        console.log(reviews.length);
        // Calculate the average rating
        average_rating = average_rating / reviews.length;
       
        let skip = (average_rating % 2 != 0) ? 1 : 0;
        
        // Fill stars with full stars up to the rating (include half stars)
        for (let i = skip; i < average_rating / 2; i++) {
            stars += '<i class="fas fa-star"></i>';
        }

        // If the rating is odd then add a half star
        if (average_rating % 2 != 0) {
            stars += '<i class="fas fa-star-half-alt"></i>';
        }

        // Fill the rest of the stars with empty stars
        for (let i = 0; i < 5 - Math.ceil(average_rating / 2); i++) {
            stars += '<i class="far fa-star"></i>';
        }
    } else {
        stars = '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
        // State that there are no reviews
        $('.event-page-review-window').append(`
            <div class="row event-page-review px-2 d-flex flex-column justify-content-center h-100 text-center">
                <span class="">There are no reviews for this event yet.</span>
            </div>
        `);
    }
    
    $(".event-page-rating-stars").html(stars);

    fillEventImageCarousel(reviews);

};

const attendEvent = (event_id) => {

    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "attendEvent",
            event_id: event_id
        },
        success: (data) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                window.location.reload();
            } else {
                showError(data);
            }
        }
    });
}

