
$(() => {
    
    console.log("profile.js loaded");

     $("#create-event-button").on("click", (e) => {
            window.location.href = "create-event.php";
        });
});

