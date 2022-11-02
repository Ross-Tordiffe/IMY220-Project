// === Global Variables ===

const urlParams = new URLSearchParams(window.location.search);
const user_id = urlParams.get('user_id');
const other_user_id = urlParams.get('other_user');


const messageObject = ({msg_id, msg_user_id, msg_message, msg_username, msg_time}) => {

    // Format the time
    msg_time = timeAgo(msg_time);

    return `
        <div class="message">
            <div class="message-id d-none">${msg_id}</div>
            <div class="message-user-id d-none">${msg_user_id}</div>
            <div class="message-content position-relative">
                <div class="message-header">
                </div>
                <div class="message-body">
                    ${msg_message}
                </div>
                <div class="message-footer position-absolute">
                    <div class="message-time">${msg_time}</div>
                </div>
            </div>
        </div>
    `;
};

//  <div class="message-sender">${msg_username}</div>

$(() => {
    getMessages();
    fetchUser(other_user_id).then((data) => {
        console.log(data);
        let profile_user_image = "public_html/img/user/" + data.user_image;
        $('.other-name').text(data.user_username)
        $('.other-img img').attr("src", profile_user_image);
    });

    $(".back-btn").click(() => {
        window.location.href = "profile.php?user_id=" + other_user_id;
    });

});

// === Promise Functions

const getMessages = async () => {

    const data = await new Promise((resolve, reject) => {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "getMessages",
                other_user_id: other_user_id
            },
            success: (data) => {
                resolve(data);
            },
            error: (error) => {
                reject(error);
            }
        }).then (data => {
            $(".message-box").empty();
            if(data.length > 0) {
                // For each message, create a new message element
                console.log(data);
                let messages = JSON.parse(data).data;
                console.log(messages);

                // For each message element, if the user is the sender, add the "message-sent" class
                // Otherwise, add the "message-received" class

                messages.forEach((message) => {
                    let messageElement = messageObject(message);
                    if (message.msg_user_id == user_id) {
                        messageElement = $(messageElement).addClass("message-sent");
                        console.log(message.msg_user_id, " and ", user_id);
                    } else {
                        console.log(message.msg_user_id, " and ", user_id);
                        messageElement = $(messageElement).addClass("message-received");
                    }

                    let newMessage = message.msg_message;
                    let oldMessage = newMessage;
                    let link = null;
                    console.log(newMessage);
                    do {
            
                        link = oldMessage.match(/((?:https?:)?\/\/)?((?:www|m)\.)?((?:youtube\.com|youtu.be))(\/(?:[\w\-]+\?v=|embed\/|v\/)?)([\w\-]+)(\S+)?/);
                        if(link != null) 
                        {
                            console.log(link);
                            oldMessage = oldMessage.replace(link[0], "");
                            console.log(oldMessage);
                            newMessage = newMessage.replace(link[0], `<iframe class="yt-embed pb-3" src="https://www.youtube.com/embed/${link[5]}" allow="fullscreen;"></iframe>`);
                            console.log(newMessage);
                        }
                    } while(link)
                    $(messageElement).find(".message-body").html(newMessage);
                    
                    $(".message-box").append(messageElement);
                });

                $('.message-box-container').scrollTop($('.message-box-container')[0].scrollHeight);

                return data;
            }

            
        });
    });
}

const fetchUser = async (user_id) => {
    const data = await new Promise((resolve, reject) => {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "fetchUserData",
                user_id: user_id
            },
            success: (data) => {
                data = JSON.parse(data);
                resolve(data.data);
            },
            error: (error) => {
                reject(error);
            }
        });
    });
    return data;
}


// === Ajax Requests ===

// --- Handle sending a message ---

$("#send-message").on("click", () => {
    let message = $(".message-input .message-text-input").val();
    if (message.length > 0) {
        $.ajax({
            url: "requests.php",
            type: "POST",
            data: {
                request: "sendMessage",
                message: message,
                other_user_id: other_user_id
            },
            success: (data) => {
                console.log(data);
                getMessages();
            },
            error: (error) => {
                showError("There was a problem sending your message");
            }
        });
        
        $("#message").val("");
    }
});

