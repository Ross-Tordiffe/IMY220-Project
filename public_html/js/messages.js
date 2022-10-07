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
            <div class="message-content">
                <div class="message-header">
                    <div class="message-sender">${msg_username}</div>
                
                </div>
                <div class="message-body">
                    ${msg_message}
                </div>
            </div>
            <div class="message-footer d-flex flex-column">
                <div class="message-time">${msg_time}</div>
            </div>
        </div>
    
    `;
};

$(() => {

    getMessages();
});

// === Promise Functions

const getMessages = async () => {

    try {
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
                    // For each message, create a new message element
                    let messages = JSON.parse(data).data;
                    console.log(messages);

                    // For each message element, if the user is the sender, add the "message-sent" class
                    // Otherwise, add the "message-received" class

                    messages.forEach((message) => {
                        let messageElement = messageObject(message);
                        if (message.msg_user_id == user_id) {
                            messageElement = $(messageElement).addClass("message-sent");
                        } else {
                            messageElement = $(messageElement).addClass("message-received");
                        }
                        $(".message-box").append(messageElement);
                    });
                    return data;
            });
        });
    } catch (error_2) {
        console.log(error_2);
    }
}
