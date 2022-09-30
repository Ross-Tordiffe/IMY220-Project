
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