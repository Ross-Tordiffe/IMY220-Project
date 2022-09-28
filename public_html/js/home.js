const EventObject = ({event_id, event_user_id, event_title, event_date, event_location, event_image, event_user_count, event_description, event_category, event_tags, event_user_name, event_user_image}) => `
<div class="event">
    <div class="card card-container mb-4 shadow event-card d-flex justify-content-center">
        <p class="d-none">${event_user_id}</p>
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
                        <span class="small">${event_user_count}</span>
                        <i class="fas fa-users ps-1"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body event-content pt-1 px-3">
            <p class="event-title card-title mb-0" onclick="openEvent()">${event_title}</p>
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

var displaying = "global";

$(() => {

    console.log("home.js loaded");
    // === Handle Events ===

    $("#bkm-global").on("click", (e) => {
        console.log("bkm-global clicked");
        if(!$("#bkm-global").hasClass("bkm-current")) {
            $("#bkm-local").removeClass("bkm-current");
            $("#bkm-global").addClass("bkm-current");
        }
        if(displaying === "local") {
            displaying = "global";
            getEvents();
        }
    });

    $("#bkm-local").on("click", (e) => {
        console.log("bkm-local clicked");
        if(!$("#bkm-local").hasClass("bkm-current")) {
            $("#bkm-global").removeClass("bkm-current");
            $("#bkm-local").addClass("bkm-current");
        }
        if(displaying === "global") {
            displaying = "local";
            getEvents();
        }
    });

    // === Handle Event Loading ===

    // promise to get events
    const getEvents = () => {
        let events = new Promise((resolve, reject) => {
            $.ajax({
                type: "POST",
                url: "requests.php",
                data: {
                    request: "getEvents",
                    scope: displaying
                },
                success: (data, status) => {
                    data = JSON.parse(data);
                    if(data.status === "success")
                    {
                        // Formatting events
                        for(let i = 0; i < data.data.length; i++) {
                            // Check entry for an empty location. if empty replace with event_website
                            if(data.data[i].event_location === "") {
                                // remove http:// or https://
                                data.data[i].event_location = data.data[i].event_website.replace(/(^\w+:|^)\/\//, '');
                                // data.data[i].event_location = data.data[i].event_website;
                            }

                            // Format the date from yyyy-mm-dd to dd/mm/yyyy
                            let date = data.data[i].event_date.split("-");
                            data.data[i].event_date = date[2] + "/" + date[1] + "/" + date[0];
                            
                        }


                        resolve(data.data);
                    }
                    else
                    {
                        reject(data.data);
                    }
                }
            });
        }).then((data) => {
            console.log(data);
            // Clear previous events from event-col classes
            $(".event-col").empty();

            // Based on the screen size distribute events between event-cols
            if($(window).width() >= 1200) {
                for(let i = 0; i < data.length; i++) {
                    if(i % 3 === 0) {
                        $(".event-col-1").append(EventObject(data[i]));
                    }
                    else if(i % 3 === 1) {
                        $(".event-col-2").append(EventObject(data[i]));
                    }
                    else {
                        $(".event-col-3").append(EventObject(data[i]));
                    }
                }
            }
            else if($(window).width() >= 992) {
                for(let i = 0; i < data.length; i++) {
                    if(i % 2 === 0) {
                        $(".event-col-1").append(EventObject(data[i]));
                    }
                    else {
                        $(".event-col-2").append(EventObject(data[i]));
                    }
                }
            }
            else {
                for(let i = 0; i < data.length; i++) {
                    $(".event-col-1").append(EventObject(data[i]));
                }
            }

        }).catch((data) => {
            showError(data);
        });
    }


    // === Function Calls ===

    // get events
    getEvents()
});