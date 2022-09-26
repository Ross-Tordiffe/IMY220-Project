// ======== Global Variables =========

var todayDate = new Date();
var today = todayDate.getFullYear() + '-' + (todayDate.getMonth() + 1) + '-' + todayDate.getDate();


// ======== Toast =========

const showError = (msg) => {
    $('#warning-text').html(msg.replace('-', ' '));
    $('.toast').toast('show');
}

// ======== Logout Request =========

$(() => {

    // on logout button click
    $('#logout').on('click', (e) => {

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




