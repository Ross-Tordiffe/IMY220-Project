
$(() => {
    
    console.log("profile.js loaded");


    const swapCards = (item) => {
        console.log("hit");
        console.log(item);
        if($(item).siblings(".item").hasClass("active")) {
            $(item).siblings(".item").removeClass("active");
            $(item).addClass("active");
        }
    }
    
    $(".profile-first").on("click", (e) => {
        console.log("hat");
        if(!$(".first-item").hasClass("active")) {
            swapCards($(".first-item"));
        }
    });
    
    $(".profile-second").on("click", (e) => {
        console.log("hit");
        if(!$(".second-item").hasClass("active")) {
            swapCards($(".second-item"));
        }
    });

});

