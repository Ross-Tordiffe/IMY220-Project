// === Promise & Request Functions ===

// === On Page Load ===

$(() => {

    let user = $("#user-id").text();
    let profile_user = $("#profile-user-id").text();

    setUser(user, profile_user);
    getEvents(user, profile_user);
    getFriends(profile_user);
    
    console.log("profile.js loaded");

    $("#create-event-button").on("click", (e) => {
        window.location.href = "create-event.php";
    });

    $(".friend-text").on("click", (e) => {
        
        // Get the user's friends and return a promise

        getFriends(profile_user);
        $("#friendsModal").modal("show");

    });

    $(".profile-modal-close").on("click", (e) => {
        $("#friendsModal").modal("hide");
    });

    // --- Accept Friend Request and create friendship on the database ---
    $("#friendsModal").on("click", ".accept-request", (e) => {
    
        let friendId = $(e.target).parent().parent().parent().find(".profile-friend-id").text();
        console.log(friendId);
        acceptFriend(friendId, profile_user);
    });

    // --- Go to the friend's profile ---
    $("#friendsModal").on("click", ".profile-friend", (e) => {
        let friendId = $(e.target).find(".profile-friend-id").text();
        // console.log(friendId);
        window.location.href = `profile.php?user_id=${friendId}`;
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



const getFriends = (profile_user) => {
        
    let friends = new Promise((resolve, reject) => {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                "request": "getFriends",
                "profile_user": profile_user
            },
            success: (data) => {
                // console.log(data);
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
            updateFriendHeader(data.data);
            console.log(data.data);
        }
        else {
            console.log(data);
        }
    }).catch((err) => {
        console.log(err);
    });
};

const getEvents = (user, profile_user) => {
    let events = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "getEvents",
                profile_user: profile_user,
                scope: "profile"
            },
            success: (data, status) => {
                // console.log(data);
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

        let other_user = user !== profile_user ? true : false;

        if(!other_user) {
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
        }

        var i = 0;
        if(!other_user) {
            i = 1;
        }
        console.log(i);
        // Based on the screen size distribute events between event-cols
        if($(window).width() >= 1200) {
            for(let j = 0; j < data.length; i++, j++) {
                console.log(i);
                if(i % 3 === 0) {
                    $(".event-col-1").append(EventObject(data[j]));
                }
                else if(i % 3 === 1) {
                    $(".event-col-2").append(EventObject(data[j]));
                }
                else {
                    $(".event-col-3").append(EventObject(data[j]));
                }
            }
        }
        else if($(window).width() >= 992) {
            for(let j = 0; j < data.length; i++, j++) {
                if(i % 2 === 0) {
                    $(".event-col-1").append(EventObject(data[j]));
                }
                else {
                    $(".event-col-2").append(EventObject(data[j]));
                }
            }
        }
        else {
            for(let j = 0; j < data.length; j++) {
                $(".event-col-1").append(EventObject(data[j]));
            }
        }
    }).catch((data) => {
        console.log(data);
        showError(data);
    });
}


const acceptFriend = (friendId, profile_user) => {
    let accept = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "acceptFriend",
                friend_id: friendId
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
        getFriends(profile_user);
    }).catch((data) => {
        console.log(data);
        showError(data);
    });
}

const rejectFriend = (friendId, profile_user) => {
    let reject = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "rejectFriend",
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
        getFriends(profile_user);
    }).catch((data) => {
        console.log(data);
        showError(data);
    });
}
                

const updateFriendHeader = (friends) => {

    let friend_accepted = friends.filter((friend) => {
        return friend.accepted === true;
    });

    let friend_requests = friends.filter((friend) => {
        return friend.accepted === false;
    });

    $(".friend-count").html(friend_accepted.length);
    
    if(friend_requests.length > 0) {
        console.log("friend requests Here");	
        $(".friend-request-count").html(friend_requests.length);
        $(".friend-request-count").removeClass("d-none");
    }
    else {
        $(".friend-requests-count").addClass("d-none");
    }
}


const setUser = (user, profile_user) => {

    // If the user is not viewing their own profile
    if(user !== profile_user) {
        $(".profile-options").html("");

        // Get the user being viewed
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "fetchUserData",
                get_user_id: profile_user
            },
            success: (data, status) => {
                console.log(data);
                data = JSON.parse(data);
                if(data.status === "success") {
                    console.log("profile-user", data.data);
                    let profile_user = data.data;
                    let profile_user_image = "public_html/img/user/" + profile_user.user_image;

                    $(".profile-header-img img").attr("src", profile_user_image);
                    $(".profile-header-name h3").html(profile_user.user_username);
                }
                else {
                    showError(data.data);
                }
                
            }
        });
    }
};
