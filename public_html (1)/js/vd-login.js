/* ======== Verify Login ======== */
(() => {
    
    let message = "";

    console.log("loaded");

    $(".splash-lg").on("click", function (e) {

        console.log("submit");
        
        if(!emailCheck()){
            e.preventDefault(); 
            showError("Invalid email address");
            return false;
        }
        else if(!passCheck()){
            e.preventDefault();
            showError("Password must be at least 8 characters");
            return false;
        }

    });


    function emailCheck() {
        var emailPattern = /^([a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð._-]{1,64})+@([a-zA-Z0-9.-]{1,})+(.[a-zA-Z.]{2,})$/;
        if(!emailPattern.test($("input[name='lg-email'").val())){
            return false;
        }
        else {
            return true;
        }
    }

    function passCheck() {

        var passLenPattern = /^(?=.{8,})/;
        if(!passLenPattern.test($("input[name='lg-password'").val())){
            return false;
        }
        else {
            return true;
        }
    }

});