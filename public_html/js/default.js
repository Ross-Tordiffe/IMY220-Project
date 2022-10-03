// ======== Global Variables =========

var todayDate = new Date();
var today = todayDate.getFullYear() + '-' + (todayDate.getMonth() + 1) + '-' + todayDate.getDate();

// --- Event Object ---
const EventObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
    <div class="event">
        <div class="card card-container mb-4 shadow event-card d-flex justify-content-center">
            <p class="d-none event-user-id">${event_user_id}</p>
            <p class="d-none event-id">${event_id}</p>
            <div class="event-image position-relative">
                <img class="img-fluid mx-auto d-block" src="public_html/img/event/${event_image}" alt="${event_image}"/>
                <div class="d-flex align-items-center event-user-loc w-100">
                    <img src="public_html/img/user/${event_user_image}" class="col-2 img-fluid event-user-img my-0 mx-2"/>
                    <div class="col-xxl-8 col-xl-7 col-lg-6 event-name-loc d-flex flex-column">
                        <div class="event-user-name">${event_user_name}</div>
                        <span class="event-location"><i class="fas fa-map-marker-alt pe-1"></i>${event_location}</span>
                    </div>
                    <div class="col-2 event-join d-flex justify-content-between align-items-center">
                        <div class="event-user-count d-flex align-items-center">
                            <span class="small pe-none">${event_user_count}</span>
                            <i class="fas fa-users ps-1 pe-none"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body event-content pt-1 px-3">
                <p class="event-title card-title mb-0">${event_title}</p>
                <div class="event-date d-flex justify-content-between">
                    <span class="small">${event_category}</span>
                    <span class="small">${event_date}<i class="fas fa-calendar-alt ps-1"></i></span>
                </div>
                <!-- small line -->
                <div class="extra">
                    <hr class="my-2"></hr>
                    <div class="event-description card-text small">${event_description}</div>
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
`;

// ======== Toast =========

const showError = (msg) => {
    $('#warning-text').html(msg.replace('-', ' '));
    $('.toast').toast('show');
}

// ======== Logout Request =========

$(() => {

    // console.log("default.js loaded");
    // on logout button click
    $('#profile-logout').on('click', (e) => {

        e.preventDefault();
    
        console.log("logout clicked");
    
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "logout"
            },
            success: (data, status) => {
                console.log(data);
                data = JSON.parse(data);
                if(data.status === "success")
                {
                    window.location.href = "index.php";
                }
                else
                {
                    showError(data.data);
                }
            }
        });
    
    });

    

    


})

// ======= General Event Handlers ========

$(".event-box").on("click", ".event-user-name", (e) => {
    e.stopPropagation()
    e.preventDefault();
    let user_id = $(e.target).parent().parent().parent().parent().find(".event-user-id").text();
    window.location.href = "profile.php?user_id=" + user_id;
});

$(".event-box").on("click", ".event-hashtag", (e) => {
    e.stopPropagation();
    e.preventDefault();
    let hashtag = $(e.target).text().substring(1);
    window.location.href = "explore.php?q=" + hashtag;
});

$(".event-box").on("click", ".event-join", (e) => {
    e.stopPropagation();
    e.preventDefault();
    let event_id = $(e.target).parent().parent().parent().parent().find(".event-id").text();
    let user_id = $("#user-id").text();
    console.log(e.target);  
    console.log("event id: ", event_id, "user id: ", user_id);
    // bookUser(e);
});
    

$(".event-box").on("click", ".event", (e) => {
    while(!$(e.target).hasClass("event-card")){
        e.target = e.target.parentElement;
    }
    let id = $(e.target).find(".event-id").text();
    if(id !== "") {
        window.location.href = "event.php?id=" + id;
    }
    else {
        window.location.href = "create-event.php";
    }
});

$("body").on("click", ".modal-close", (e) => {
    console.log("close clicked");
    $(".modal").modal("hide");
});


