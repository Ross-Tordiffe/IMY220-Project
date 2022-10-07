// === Global Variables ===

var displaying = "my-events";

// === On Page Load ===

$(() => {

    let user = $("#user-id").text();
    let profile_user = $("#profile-user-id").text();

    setUser(user, profile_user);
    getEvents(user, profile_user);
    getFriends(profile_user);
    

    $(".friend-text").on("click", (e) => {

        // Get the user's friends and return a promise
        getFriends(profile_user);
        $("#friendsModal").modal("show");
    });

    // --- Accept Friend Request and create friendship on the database ---
    $("#friendsModal").on("click", ".accept-request", (e) => {
        e.preventDefault();
        e.stopPropagation();
        let friendId = $(e.target).parent().parent().parent().find(".profile-friend-id").text();
        acceptFriend(friendId, profile_user);
    });

    // --- Go to the friend's profile ---
    $("#friendsModal").on("click", ".profile-friend", (e) => {
        while(!$(e.target).hasClass("profile-friend")) {
            e.target = e.target.parentElement;
        }
        let friendId = $(e.target).find(".profile-friend-id").text();
        window.location.href = `profile.php?user_id=${friendId}`;
    });

    $("#profile-add-friend").on("click", (e) => {
        
        // print profile-add-friend classes
        // if the profile-add-friend class is "add-friend"
        if($("#profile-add-friend").hasClass("friend")) {
            $(".confirmation-modal-text p").text("Do you want to unfriend this user?");
            $("#confirmationModal").modal("show");

            $("#confirmationModal").on("click", ".confirmation-modal-btn", (e) => {
                removeFriend(profile_user);
                getFriends(profile_user);
                $(".modal").modal("hide");
            });

        }
        else {
            friendRequest(profile_user);
            getFriends(profile_user);
        }
    });

    // // --- Cancel Friend Request ---
    // $("#friendsModal").on("click", ".cancel-request", (e) => {
    //     let friendId = $(e.target).parent().parent().parent().find(".profile-friend-id").text();
    //     cancelFriend(friendId, profile_user);
    // });

    if(user === profile_user) {
        // --- Change profile image ---
        $(".profile-img-container").on("click", (e) => {
            e.stopPropagation();
            $("#profile-img-input").click();
            // do not propogate to parent

        });

        $("#profile-img-input").on("click", (e) => {
            e.stopPropagation();
        });

        $("#profile-img-input").on("change", (e) => {
            let file = e.target.files[0];
            if(handleImageFile(file)){

            
            let formData = new FormData();
            formData.append("request", "changeProfilePicture");
            formData.append("file", file);
                
                $.ajax({
                    url: "requests.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: (data) => {
                        console.log(data);
                        let response = JSON.parse(data);
                        if (response.status === "success") {
                            url = response.data;
                            $(".profile-img-container img").attr("src", url);
                        }
                    }
                });

            }
            else {
                alert("Invalid file type");
            }
        });
    }
    else {
        $(".profile-img-container").css("cursor", "default");
        $(".profile-img-container").css("pointer-events", "none");
    }

    $("#bkm-my-events").on("click", (e) => {
        if(!$("#bkm-my-events").hasClass("bkm-current")) {
            $("#bkm-groups").removeClass("bkm-current");
            $("#bkm-my-events").addClass("bkm-current");
        }
        if(displaying === "groups") {
            displaying = "my-events";
            getEvents();
        }
        else if (displaying === "group") {
            $(".group-header").empty();
            displaying = "my-events";
            getEvents();
        }
    });

    $("#bkm-groups").on("click", (e) => {
        if(!$("#bkm-groups").hasClass("bkm-current")) {
            $("#bkm-my-events").removeClass("bkm-current");
            $("#bkm-groups").addClass("bkm-current");
        }
        if(displaying === "my-events") {
            displaying = "groups";
            getGroups();
        }
        else if (displaying === "group") {
            $(".group-header").empty();
            displaying = "groups";
            getGroups();
        }
    });

    $(".profile-container").on("click", ".group-card-template", () =>{
        
        // Show create group modal
        $("#createGroupModal").modal("show");

        $(".create-group-form-container").on("click", ".create-group-submit", (e) => {
          
            let name = $("#group-title").val();
            let description = $("#group-description").val();
            let formData = new FormData();
            formData.append("request", "createGroup");
            formData.append("group_name", name);
            formData.append("group_description", description);
    
            $.ajax({
                url: "requests.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: (data) => {

                    let response = JSON.parse(data);
                    if (response.status === "success") {
                        $("#createGroupModal").modal("hide");
                        getGroups();
                    }
                }
            });
        });
        
    });

    $("#group-title").on("keyup", (e) => {
        $("#group-title-counter").text(`${e.target.value.length}/50`);
    });

    $("#group-description").on("keyup", (e) => {
        $("#group-description-counter").text(`${e.target.value.length}/120`);
    });

    $(".profile-container").on("mouseenter", ".event-group", (e) => {
        // float the description up
        $(e.currentTarget).find(".group-description").removeClass("d-none");
        $(e.currentTarget).find(".group-description").removeClass("reverse-animate");
        $(e.currentTarget).find(".group-description").addClass("animate");
        
    });

    $(".profile-container").on("mouseleave", ".event-group", (e) => {
        // float the description down
        let event_group = $(e.currentTarget);
        $(event_group).css("pointer-events", "none");
        setTimeout(() => {
            $(e.currentTarget).find(".group-description").addClass("d-none");
            $(e.currentTarget).find(".group-description").removeClass("animate");
            $(event_group).css("pointer-events", "auto");
        }, 500);
        
        $(e.currentTarget).find(".group-description").addClass("reverse-animate");
    });

    $(".profile-container").on("click", ".event-group", (e) => {
        showGroupEvents($(e.currentTarget).find(".group-id").text());
    });

    $(".profile-container").on("click", ".event-delete-icon", (e) => {
        e.stopPropagation();
        let event_id = $(e.currentTarget).parent().find(".event-id").text();
        let group_id = $(".group-header-id").text();
        removeEventFromGroup(event_id, group_id);   
    });

    $(".group-header").on("click", ".group-header-back-arrow", (e) => {
        $(".group-header").empty();
        getGroups();
    });

    $(".group-header").on("click", ".group-header-delete", (e) => {
   
        $(".confirmation-modal-text p").text("Do you want to delete this group?");
        $("#confirmationModal").modal("show");

        $("#confirmationModal").on("click", ".confirmation-modal-btn", (e) => {
            deleteGroup($(".group-header-id").text());
            $(".modal").modal("hide");
        });

    });

    $(".modal").on("click", ".goHome", (e) => {
        window.location.href = ("home.php?user_id=" + user);
    });

    $(".message-btn").on("click", (e) => {
        // Go to messages page
        window.location.href = ("messages.php?user_id=" + user + "&other_user=" + profile_user);
    });

});


// === Helper Functions ===

const displayFriends = (friends) => {

    let user = $("#user-id").text();
    let profile_user = $("#profile-user-id").text();

    $(".profile-friends").empty();
    let friendsHTML = "";

    if(!(user === profile_user)) {
        // Remove all not accepted friends
        friends = friends.filter((friend) => {
            return (friend.accepted === true);
        });
    }

    console.log(friends);

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
    if(friends.length === 0 && user === profile_user) {
        $(".profile-friends").html(
            `
                <div class="no-friends-message w-100 text-center p-4">
                    <span>No friends to show. Go out and make some!</span>
                    <button class="btn goHome">Home</button>
                </div>
            `
        )
    }
    else if (friends.length === 0) {
        $(".profile-friends").html(
            `
                <div class="no-friends-message w-100 text-center p-4">
                    <span>No friends to show.</span>
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
            console.log(data.data);
            displayFriends(data.data);
            updateFriendHeader(data.data);
            let user = $("#user-id").text();

            // look for the user in the friends list
            let friend = data.data.filter((friend) => {
                return friend.user_id == user;
            });

            // if the user is not the profile user, but is friends with the profile user, show the message button and change the friend icon to a checkmark
            if(user !== profile_user && friend.length > 0) {
                $("#profile-message").removeClass("d-none");
                $("#profile-add-friend i").removeClass("fa-user-plus");
                $("#profile-add-friend i").addClass("fa-check");
                $("#profile-add-friend").addClass("friend");
            }
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
        // Clear previous events from event-col classes
        $(".event-col").empty();

        let other_user = user !== profile_user ? true : false;

        if(!other_user) {
            // make first event a create event template
            let createEventHTML = `
                <div class="event">
                    <div class="card event-card event-card-template">
                        <div class="card-body d-flex justify-content-center align-items-center position-relative">
                            <div class="event-card-template-plus d-flex justify-content-center align-items-center position-absolute">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="row event-card-background-square d-flex justify-content-center position-absolute">
                                <div class="col-12 top-0 start-0 mt-2"></div>
                                <div class="col-12 start-0 m-1"></div>
                                <div class="col-12 bottom-0 start-0 mb-1"></div>
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


    
        // Based on the screen size distribute events between event-cols
        if($(window).width() >= 1200) {
            for(let j = 0; j < data.length; i++, j++) {
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
        showError(data);
    });
}

const handleImageFile = (file) => {
    console.log(file);
    // Check if the file is one of the allowed types (jpg, jpeg, png)
    if(file.type === "image/jpeg" || file.type === "image/png") {
        // Check if the file is less than 2MB
        if(file.size < 2000000) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
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

getGroups = (user, profile_user) => {
    let groups = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "getGroups",
                profile_user: profile_user,
                scope: "profile"
            },
            success: (data, status) => {
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
        // Clear previous groups from group-col classes
        $(".event-col").empty();

        let other_user = user !== profile_user ? true : false;

        if(!other_user) {
            // make first group a create group template
            let createGroupHTML = `
                <div class="group">
                    <div class="card group-card group-card-template">
                        <div class="card-body d-flex justify-content-center align-items-center position-relative">
                            <div class=" d-flex justify-content-center align-items-center position-absolute">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="row group-card-background-squares">
                                <div class="col-6 group-card-background-square position-absolute top-0 start-0 m-1"></div>
                                <div class="col-6 group-card-background-square position-absolute top-0 end-0 m-1"></div>
                                <div class="col-6 group-card-background-square position-absolute bottom-0 end-0 m-1"></div>
                                <div class="col-6 group-card-background-square position-absolute bottom-0 start-0 m-1"></div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            // Append create group card to first group-col
            $(".event-col").first().append(createGroupHTML);
        }

        var i = 0;
        if(!other_user) {
            i = 1;
        }
        // Based on the screen size distribute groups between group-cols
        if($(window).width() >= 1200) {
            for(let j = 0; j < data.length; i++, j++) {
                if(i % 3 === 0) {
                    $(".event-col-1").append(GroupObject(data[j]));
                }
                else if(i % 3 === 1) {
                    $(".event-col-2").append(GroupObject(data[j]));
                }
                else {
                    $(".event-col-3").append(GroupObject(data[j]));
                }
            }
        }
        else if($(window).width() >= 992) {
            for(let j = 0; j < data.length; i++, j++) {
                if(i % 2 === 0) {
                    $(".event-col-1").append(GroupObject(data[j]));
                }
                else {
                    $(".event-col-2").append(GroupObject(data[j]));
                }
            }
        }
        else {
            for(let j = 0; j < data.length; j++) {
                $(".event-col-1").append(GroupObject(data[j]));
            }
        }
    }).catch((data) => {
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
        getFriends(profile_user);
    }).catch((data) => {
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
        getFriends(profile_user);
    }).catch((data) => {
        showError(data);
    });
}
                

const updateFriendHeader = (friends) => {

    let user = $("#user-id").text();
    let profile_user = $("#profile-user-id").text();

    let friend_accepted = friends.filter((friend) => {
        return friend.accepted === true;
    });

    let friend_requests = friends.filter((friend) => {
        return friend.accepted === false;
    });

    $(".friend-count").html(friend_accepted.length);
    
    if(friend_requests.length > 0 && profile_user === user) {
        console.log("profile_user: " + profile_user + " user: " + user);
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
        // Get the user being viewed
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "fetchUserData",
                user_id: profile_user
            },
            success: (data, status) => {
                data = JSON.parse(data);
                if(data.status === "success") {
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

const friendRequest = (profile_user) => {

    // Send friend request
    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "friendRequest",
            friend_id: profile_user
        },
        success: (data, status) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                $(".profile-add-friend").addClass("friend")
                $(".profile-add-friend i").removeClass("fa-user-plus");
                $(".profile-add-friend i").addClass("fa-user-check");
            }
            else {
                showError(data.data);
            }
        }
    });
}

const removeFriend = (profile_user) => {

    // Remove friend
    $.ajax({
        url: "requests.php",
        type: "POST",
        data: {
            request: "removeFriend",
            friend_id: profile_user
        },
        success: (data, status) => {
            data = JSON.parse(data);
            if(data.status === "success") {
                $(".profile-add-friend").removeClass("friend")
                $(".profile-add-friend i").removeClass("fa-user-check");
                $(".profile-add-friend i").addClass("fa-user-plus");
            }
            else {
                showError(data.data);
            }
        }
    });
}

const showGroupEvents = (group_id) => {
    let getEvents = new Promise((resolve, reject) => {
        $.ajax({
            type: "POST",
            url: "requests.php",
            data: {
                request: "getGroup",
                group_id: group_id
            },
            success: (data, status) => {
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

        displaying = "group";

        

        $(".event-col").empty();
        $(".group-header").empty();
        // append the group header
        $(".group-header").append(
            $(`<div></div>`, {
                html: 
                `
                
                    <div class="d-flex align-items-center">
                        <div class="group-header-back-arrow pe-4 ps-2 fs-2">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                        <div>
                            <div class="group-header-title">
                                <h3>${data.group_title}</h3>
                            </div>
                            <div class="group-header-description">
                                <p class="m-0">${data.group_description}</p>
                            </div>
                        </div>
                    </div>
                    <div class="group-header-delete mx-2 fs-2">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                    <div class="group-header-id d-none">${data.group_id}</div>
                `,
                class: `group-header-content p-2 d-flex align-items-center justify-content-between`,
            })
        );

        $(".event-col-1").html("");
        $(".event-col-2").html("");
        $(".event-col-3").html("");

        data = data.group_events;

        if($(window).width() >= 1200) {
            for(let j = 0, i = 0; j < data.length; i++, j++) {
                if(data[j].event_location === ""){
                    data[j].event_location = data[j].event_website;
                }
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
            for(let j = 0, i = 0; j < data.length; i++, j++) {
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

        // append delete icon to each event
        $(".event").each((index, element) => {
            $(element).append(
                $(`<div></div>`, {
                    html: `<i class="fas fa-times"></i>`,
                    class: `event-delete-icon position-absolute top-0 end-0 fs-2 pe-3`,
                })
            );
        });

    }).catch((data) => {
        showError(data);
    });
}

const deleteGroup = (group_id) => {

    let user = $("#user-id").text();

    $.ajax({
        type: "POST",
        url: "requests.php",
        data: {
            request: "deleteGroup",
            group_id: group_id
        },
        success: (data, status) => {
            data = JSON.parse(data);
            if(data.status === "success")
            {
                window.location.href = `profile.php?user_id=${user}`;
            }
            else
            {
                showError(data.data);
            }
        }
    });
}

const removeEventFromGroup = (event_id, group_id) => {
    $.ajax({
        type: "POST",
        url: "requests.php",
        data: {
            request: "removeEventFromGroup",
            event_id: event_id,
            group_id: group_id
        },
        success: (data, status) => {
            data = JSON.parse(data);
            if(data.status === "success")
            {
                showGroupEvents(group_id);
            }
            else
            {
                showError(data.data);
            }
        }
    });
}