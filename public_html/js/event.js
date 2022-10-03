

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

    // === open review creation modal === 
    $(".event-container").on("click", ".add-review-btn", () => {
        console.log("clicked");
        $("#eventReviewModal").modal("show");
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

// const user = new Promise((resolve, reject) => {
    
//         $.ajax({
//             url: 'requests.php',
//             type: 'POST',
//             data: {
//                 request: 'fetchUserData',
//                 user_id: $('#user-id').text()
//             },
//             success: (data) => {
//                 console.log(data);
//                 const user = JSON.parse(data).data;
//                 console.log(user);
//                 resolve(user);
//             },
//             error: (err) => {
//                 console.log(err);
//             }
//         });
// });

// const getUser = user.then(user);
