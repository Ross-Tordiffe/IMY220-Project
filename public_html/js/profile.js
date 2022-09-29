// === Event Object === 

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

// === Promise & Request Functions ===

// === On Page Load ===

$(() => {

    // $("#friendsModal").modal("show");

    getEvents();
    
    console.log("profile.js loaded");

    $("#create-event-button").on("click", (e) => {
        window.location.href = "create-event.php";
    });

    $(".friend-text").on("click", (e) => {
        
        // Get the user's friends and return a promise
        const getFriends = () => {
        
            let friends = new Promise((resolve, reject) => {
                $.ajax({
                    url: "requests.php",
                    type: "POST",
                    data: {
                        "request": "getFriends"
                    },
                    success: (data) => {
                        console.log(data);
                        return data;
                    },
                    error: (err) => {
                        console.log(err);
                    }
                }).then((data) => {
                    resolve(data);
                });
            }).then((data) => {
                data = JSON.parse(data);
                if(data.status === "success") {
                    displayFriends(data.data);
                    console.log(data.data);
                }
                else {
                    console.log(data);
                }
            }).catch((err) => {
                console.log(err);
            });
        };

        getFriends();
        $("#friendsModal").modal("show");

    });

    $(".profile-modal-close").on("click", (e) => {
        $("#friendsModal").modal("hide");
    });

});


// === Helper Functions ===

const displayFriends = (friends) => {
    $(".profile-friends").empty();
    let friendsHTML = "";
    for(let i = 0; i < friends.length; i++) {
        let userImage = "public_html/img/user/" + friends[i].user_image;
        let requested = friends[i].accepted === false ? 
        `
            <!-- inline accept button or cancel request -->
            <div class="d-inline">
                <button class="btn btn-sm accept-request px-2 py-0 d-inline">accept</button>
                <i class="fas fa-times ps-2 cancel-request"></i>
            </div>
        ` : "";

        friendsHTML += `
            <li class="profile-friend">
                <div class="profile-friend-image">
                    <img src="${userImage}" alt="Profile Image">
                </div>
                <div class="profile-friend-name ps-2 d-flex justify-content-between align-items-center w-100 me-3">
                    <span>${friends[i].user_username}</span>
                    ${
                       requested
                    }
                </div>
                <div class="d-none profile-friend-id">${friends[i].user_id}</div>
            </li>
        `;
    }
    $(".profile-friends").append(friendsHTML);
    if(friends.length === 0) {
        $(".profile-friends").html(
            `
                <div class="no-friends-message w-100 text-center p-4">
                    <span>No friends to show. Go out and make some!</span>
                    <button class="btn" onclick="window.location.href='home.php?<?=$_SESSION['user_id']?>'">Home</button>
                </div>
            `
        )
    }
}

const getEvents = () => {
    let events = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "getEvents",
                scope: "profile"
            },
            success: (data, status) => {
                console.log(data);
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

        // make first event a create event template
        let createEventHTML = `
            <div class="event">
                <div class="card event-card event-card-template">
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <div class=" d-flex justify-content-center align-items-center">
                            <i class="fas fa-plus"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Append create event card to first event-col
        $(".event-col").first().append(createEventHTML);

        // Based on the screen size distribute events between event-cols
        if($(window).width() >= 1200) {
            for(let i = 0; i < data.length; i++) {
                if(i % 3 === 0) {
                    $(".event-col-2").append(EventObject(data[i]));
                }
                else if(i % 3 === 1) {
                    $(".event-col-3").append(EventObject(data[i]));
                }
                else {
                    $(".event-col-1").append(EventObject(data[i]));
                }
            }
        }
        else if($(window).width() >= 992) {
            for(let i = 0; i < data.length; i++) {
                if(i % 2 === 0) {
                    $(".event-col-2").append(EventObject(data[i]));
                }
                else {
                    $(".event-col-1").append(EventObject(data[i]));
                }
            }
        }
        else {
            for(let i = 0; i < data.length; i++) {
                $(".event-col-1").append(EventObject(data[i]));
            }
        }
    }).catch((data) => {
        console.log(data);
        showError(data);
    });
}


const acceptFriend = (friendId) => {
    let accept = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "acceptFriend",
                friendId: friendId
            },
            success: (data, status) => {
                console.log(data);
                data = JSON.parse(data);
                if(data.status === "success")
                {
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
        getFriends();
    }).catch((data) => {
        console.log(data);
        showError(data);
    });
}
