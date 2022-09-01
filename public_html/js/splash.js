// ======== Page setup ========
$(".splash-container:nth-child(1)").css("height", $(".splash-table").height());
$(".lgsu-runner").css("height", $(".item").height() + 200);

// ======== Login/Signup Card Swap ========
$(".item").on("click", (e) => {
    swapLgsu(this);
});

const swapLgsu = (item) => {
    if($(item).siblings(".item").hasClass("active")) {
        $(item).siblings(".item").removeClass("active");
        $(item).addClass("active");
    }
}
// ======== Float Up Effect ========

// $.fn.isInViewport = ( tolerance ) => {
//     var elementTop = $(this).offset().top() + tolerance;
//     var elementBottom = elementTop + $(this).outerHeight();

//     var viewportTop = $(window).scrollTop();
//     var viewportBottom = viewportTop + $(window).height();

//     return elementBottom > viewportTop && elementTop < viewportBottom;s
// };

$(window).on("scroll", () => {

    let hT = $('.lgsu-container').offset().top + 200,
        hH = $('.lgsu-container').outerHeight(),
        wH = $(window).height(),
        wS = $(this).scrollTop();
    if (wS > (hT+hH-wH)){
        $('.lgsu-container').addClass('in-view');
    }
 });



// ======== Go to Login/Signup ========

const goToLogin = (signup) => {
    $(".lgsu-container")[0].scrollIntoView();
    if(signup) {
        if(!$(".su-item").hasClass("active")) {
            swapLgsu($(".su-item"));
        }
    }
    else {
        if(!$(".lg-item").hasClass("active")) {
            swapLgsu($(".lg-item"));
        }
    }
}