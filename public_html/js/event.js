

const EventPageObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
    <div class="row event-view">
        <div class="col-12 event-info">
            <div class="event-page-user-id d-none">${event_user_id}</div>
            <div class="event-page-id d-none">${event_id}</div>
            <div class="row event-page-back p-2">
                <div class="col-12 col-md-6 event-page-image p-0 position-relative">
                    <img class="img-fluid" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                </div>
                <div class="col-12 col-md-6 event-page-details">
                    <div class="row">
                        <div class="col-12 event-page-title">
                            <h1>${event_title}</h1>
                        </div>
                        <div class="col-12 col-lg-12 event-page-date">
                            <span class="small"><i class="fas fa-calendar-alt pe-2"></i>${event_date}</span>
                        </div>
                        <div class="col-12 col-lg-12 event-page-location">
                            <span class="small"><i class="fas fa-map-marker-alt pe-2"></i>${event_location}</span>
                        </div>
                        <div class="col-12 event-page-category">
                            <span class="small">${event_category}</span>
                        </div>
                        <div class="col-12 event-page-description">
                            <p class="small">${event_description}</p>
                        </div>
                        <div class="col-12 event-page-hashtags">
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
            <div class="col-12 event-page-reveiws">
                <div class="row">
                    <div class="col-12 event-page-reveiws-title">
                        <h2>Reviews</h2>
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
            $('.event-page-image').append(`
                <div class="col-12 event-page-edit position-absolute">
                    <button class="btn"><i class="fas fa-edit"></i></button>
                </div>
            `);
        }

        // Edit icons
        $('.event-page-image').append(`
            <div class="col-12 event-page-edit-image edit-icon position-absolute">
                <button class="btn"><i class="fas fa-edit"></i></button>
            </div>
        `);

        $('.event-page-edit').click(() => {
            window.location.href = 'create-event.php?event_id=' + event.event_id;
        });
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
            console.log(event);
            $('.event-container').html(EventPageObject(event));
            resolve(event);
        },
        error: (err) => {
            console.log(err);
        }
    });
});

const getEvent = eventPage.then(event);