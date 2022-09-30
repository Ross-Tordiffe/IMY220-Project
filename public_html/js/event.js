

const EventPageObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
    <div class="row event-view">
        <div class="col-12 event-info">
            <div class="event-page-user-id d-none">${event_user_id}</div>
            <div class="event-page-id d-none">${event_id}</div>
            <div class="row">
                <div class="col-12 col-md-6 event-image">
                    <img class="img-fluid" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                </div>
                <div class="col-12 col-md-6 event-details">
                    <div class="row">
                        <div class="col-12 event-title">
                            <h1>${event_title}</h1>
                        </div>
                        <div class="col-12 event-date">
                            <span class="small">${event_date}<i class="fas fa-calendar-alt ps-1"></i></span>
                        </div>
                        <div class="col-12 event-location">
                            <span class="small"><i class="fas fa-map-marker-alt pe-1"></i>${event_location}</span>
                        </div>
                        <div class="col-12 event-category">
                            <span class="small">${event_category}</span>
                        </div>
                        <div class="col-12 event-description">
                            <p class="small">${event_description}</p>
                        </div>
                        <div class="col-12 event-hashtags">
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
                <div class="col-12 event-reveiws">
                    <div class="row">
                        <div class="col-12 event-reveiws-title">
                            <h2>Reviews</h2>
                        </div>
                    </div>
                </div>
                
            </div>

        </div>
    </div>
`;

$(() => {

    // ======== Load Event Page =========

    eventPage();

});

// === Ajax Requests ===

const eventPage = () => {

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
        },
        error: (err) => {
            console.log(err);
        }
    });
}