

const EventPageObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
    <div class="row event-view">
        <div class="col-12 event-info">
            <div class="event-page-user-id d-none">${event_user_id}</div>
            <div class="event-page-id d-none">${event_id}</div>
            <div class="row event-page-back p-2">
                <div class="col-12 col-md-6 event-page-content p-2 position-relative">
                    <img class="img-fluid p-0" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                    <div class="event-page-details d-flex flex-column p-1 pt-1">
                        <div class="event-page-title">
                            <h2>${event_title}</h2>
                        </div>
                        <div class="event-page-date">
                            <span class="small"><i class="fas fa-calendar-alt pe-2"></i>${event_date}</span>
                        </div>
                        <div class="event-page-location">
                            <span class="small"><i class="fas fa-map-marker-alt pe-2"></i>${event_location}</span>
                        </div>
                        <div class="event-page-category">
                            <span class="small">${event_category}</span>
                        </div>
                        <div class="event-page-description">
                            <p class="small">${event_description}</p>
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
                <div class="col-12 col-md-6 event-page-reviews p-0">
                    <div class="row event-page-user m-0 h-100 flex-column">
                        <div class="col-12 event-page-join-header p-2 border-start-0 d-flex justify-content-evenly">
                            <div class="event-page-join-event d-flex align-items-center">
                                <button class="btn btn-primary btn-sm">Attend</button>
                                <i class="fas fa-users ps-1 pe-2"></i>
                                <span class="small event-page-user-count">${event_user_count}</span>
                            </div>
                            <div class="event-page-add-to-group d-flex align-items-center">
                                <button class="btn btn-primary btn-sm">Add to group</button>
                                <i class="fas fa-border-all ps-1 pe-2"></i>
                            </div>
                        </div>
                        <div class="event-page-profile-line d-flex justify-content-center">
                            <div></div class="border-top-2">
                        </div>
                        <div class="col-12 event-page-user-info p-2 border-start-0 d-flex align-items-center justify-content-center">
                            <div class="col-2 col-md-2 event-page-user-image">
                                <img class="img-fluid" src="public_html/img/user/${event_user_image}" alt="${event_user_image}"/>
                            </div>
                            <div class="col-12 col-md-10">
                                <div class="event-page-user-name">
                                    <h3>${event_user_name}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 event-page-review-box flex-grow-1 d-flex justify-content-center">
                            <div class="event-page-review-window p-2 w-100"></div>
                        </div>
                        <div class="col-12 event-page-add-review p-2 flex-shrink-1 d-flex justify-content-center align-items-center">
                            <button class="add-review-btn btn btn-primary btn-sm py-0">Add review</button>
                        </div>
                    </div>
             
                        
                </div>
            </div>
        </div>
    </div>
`;

$(() => {

    // ======== Load Event Page =========

    // === Edit Event Functions ===
    
    getEvent.then((event) => {

        let event_user_id = $('.event-page-user-id').text();
        let user_id = $('#user-id').text();

        // if the user is the owner of the event then show the edit event button
        console.log(event_user_id);
        if (event_user_id == user_id) {
            $('.event-page-content').append(`
                <div class="col-12 event-page-edit position-absolute">
                    <button class="btn"><i class="fas fa-edit"></i></button>
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
    });

    // === Event Page reviews ===

    getreviews.then((reviews) => {
        
        reviews.forEach((review) => {
            $('.event-page-reviews').append(`
                <div class="row event-page-review p-2">
                    <div class="col-2 col-md-1 event-page-review-image">
                        <img class="img-fluid" src="public_html/img/user/${review.user_image}" alt="${review.user_image}"/>
                    </div>
                    <div class="col-10 col-md-11 event-page-review-content">
                        <div class="event-page-review-user-name">
                            <h3>${review.user_name}</h3>
                        </div>
                        <div class="event-page-review-text">
                            <p>${review.review_text}</p>
                        </div>
                    </div>
                </div>
            `);
        });
    });

    // === Open review creation modal === 
    $(".event-container").on("click", ".add-review-btn", () => {
        console.log("clicked");
        $("#eventReviewModal").modal("show");
    });

    // === Handle review Stars ===

    var cur = $(".star-box");
    console.log(cur);
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
                    console.log($(this), cur);
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
            console.log(imageFile);
        }
    });

    $("#event-review-file").on("change", (e) => {
        let file = e.target.files;
        handleImageFile(file);
    });

    $(".event-review-image-box").on("click", (e) => {
        $("#event-review-file").click();
        // do not propegate to parent 

    });
    //     console.log(e.target);
    //     
    // });

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
                console.log(event);
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
            console.log(reviews);
            resolve(reviews);
        },
        error: (err) => {
            console.log(err);
        }
    });
});

const getreviews = reviews.then(reviews);

// === Helper Functions ===

// --- Event Rating Stars ---

const fillStars = (cur, star, curRating) => {

    // console.log(curRating);
    console.log("star", star);
    // console.log(cur);

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

// --- Image handling --- //

const handleImageFile = (file) => {

    console.log("handleImageFile");

    // Check if the file is one of the allowed types (jpg, jpeg, png)
    if(file[0].type === "image/jpeg" || file[0].type === "image/png") {
        // Check if the file is less than 2MB
        if(file[0].size < 2000000) {
            let reader = new FileReader();
            reader.readAsDataURL(file[0]);
            reader.onload = (e) => {
                console.log("IMAGE: ", e.target.result);
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




// const updateRating = (star, article) => {

// 	rating = $(star).index() + 1;
// 	url = $(article).find("a").attr("href");
// 	id = $(article).find(".id_store").html();

// 	console.log("rating: " + rating + " url: " + url + " id: " + id);
 
// 	$.ajax({
// 		// "url": "/u21533572/api.php",
// 		"url": "requests.php",
// 		"type": "POST",
// 		"data": JSON.stringify({
// 			"key": userKey,
// 			"type": "rate",
// 			"rating": rating,
// 			"url": url,
// 			"id": id,
// 		}),
// 		contentType: 'application/json',
// 		username:'u21533572',
// 		password:'Un5t4b13Un1v3r5317?',
// 	}).done(function (json) {
// 		if(json != null){
// 			json = JSON.parse(json)
// 		}
// 		console.log(json);
// 		console.log(json.data['rating'])
// 		$(article).find(".rating_store").html(json.data['rating']);
// 	}).fail(function(xhr, status, error) {
// 	console.log("Error: " + error);
// 	console.log("Status: " + status);
// 	console.dir(xhr);
// 	});
// }
