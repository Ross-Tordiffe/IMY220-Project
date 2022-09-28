// === Promise & Request Functions ===



// === On Page Load ===

$(() => {
    
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

    });



});


// === Helper Functions ===

const displayFriends = (friends) => {
    let friendsHTML = "";
    for(let i = 0; i < friends.length; i++) {
        let userImage = "public_html/img/user/" + friends[i].user_image;


        friendsHTML += `
            <div class="profile-friend">
                <div class="profile-friend-image">
                    <img src="${userImage}" alt="Profile Image">
                </div>
                <div class="profile-friend-name ps-2">
                    <span>${friends[i].user_username}</span>
                </div>
            </div>
        `;
    }
    $(".profile-friends").append(friendsHTML);
}

