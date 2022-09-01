/* ======== Verify Login ======== */

var message = "";

$(() => {
    
    $("#su-submit").on("click", (e) => {

        e.preventDefault(); // Prevent Default Submission

        console.log("submit signup");

        if(!nameCheck() || !emailCheck() || !passCheck() || !passConfCheck)
        {
            console.log(message);
            showError(message);
            return false;
        }
        else {

            let form = $(".su-inputs")[0];
            let formData = new FormData(form);
            formData.append("request", "signup");

            $.ajax({
                type: "POST",
                url: "requests.php",
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: (data, status) => {
                    console.log(data);
                    data = JSON.parse(data);
                    if(data.status === "success")
                    {
                        window.location.href = "home.php?user_id=" + data.user_id;
                    }
                    else
                    {
                        showError(data.message);
                    }
                }
            });
        }

    });
});

// ======== Validation Functions ========

const nameCheck = () => 
{

    var namePattern = /^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð'-]*$/;
 
    if($("input[name='su-firstname'").val().length <= 0)
    {
        message = "Please enter your first name";
        console.log("asd")
        return false;
    }
    else if($("input[name='su-firstname'").val().length > 50)
    {
        message = "First name cannot be more than 50 characters";
        return false;
    }
    else if(!(namePattern.test($("input[name='su-firstname'").val())))
    {
        message = "Name must be alphabetical letters only";
        return false;
    }
    else if($("input[name='su-lastname'").val().length > 50)
    {
        message = "Last name cannot be more than 50 characters";
        return false;
    }
    else if($("input[name='su-lastname'").val().length <= 0)
    {
        message = "Please enter your last name";
        return false;
    }
    else if(!(namePattern.test($("input[name='su-lastname'").val())))
    {
        message = "Surname must be alphabetical letters only";
        return false;
    }

    return true;
}

const usernameCheck = () => 
{
    var usernamePattern = /^[a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð._\-!@#\$%\^&\*-]{1,50}$/;
    var usernamePattern2 = /(?!.*[_.]{2})[^_.].*[^_.]$/;
    if($("input[name='su-username'").val().length <= 0)
    {
        message = "Please enter a username";
        return false;
    }
    else if($("input[name='su-username'").val().length > 80){
        message = "Username cannot be more than 80 characters";
        return false;
    }
    else if(!(usernamePattern.test($("input[name='su-username'").val())))
    {
        message = "Username must be characters and symbols only";
        return false;
    }
    else if(!(usernamePattern2.test($("input[name='su-username'").val())))
    {
        message = "Username must not begin or end with an underscore or period";
        return false;
    }
    return true;
}
 
const emailCheck = () => 
{
    var emailPattern = /^([a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð._-]{1,64})+@([a-zA-Z0-9.-]{1,})+(.[a-zA-Z.]{2,})$/;
    if(!emailPattern.test($("input[name='su-email'").val())){
        message = "Please enter a valid email address";
        return false;
    }
    else if($("input[name='su-email'").val().length <= 0){
        message = "Please enter an email address";
        return false;
    }
    else if ($("input[name='su-email'").val().length > 80)
    {
        message = "Email cannot be more than 80 characters";
        return false;
    }
    return true;
}

const passCheck = () => 
{
    var passPattern = /^(?=.*[a-zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž])(?=.*[A-ZÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
    var passLenPattern = /^(?=.{8,})/;
    var passLowPattern = /^(?=.*[a-zàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšž])/;
    var passUpPattern = /^(?=.*[A-ZÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð])/;
    var passNumPattern = /^(?=.*[0-9])/;
    var passSymPattern = /^(?=.*[!@#\$%\^&\*])/;
    
    if(!passPattern.test($("input[name='su-password'").val()))
    {
        if(!passLenPattern.test($("input[name='su-password'").val()))
        {
            message = "Password must be at least 8 characters";
            return false;
        }
        else if($("input[name='su-password'").val().length > 100)
        {
            message = "Password cannot be more than 100 characters";
            return false;
        }
        else if(!passLowPattern.test($("input[name='su-password'").val()))
        {
            message = "Password must contain at least one lowercase letter";
            return false;
        }
        else if(!passUpPattern.test($("input[name='su-password'").val()))
        {
            message = "Password must contain at least one uppercase letter";
            return false;
        }
        else if(!passNumPattern.test($("input[name='su-password'").val()))
        {
            message = "Password must contain at least one number";
            return false;
        }
        else if(!passSymPattern.test($("input[name='su-password'").val()))
        {
            message = "Password must contain at least one symbol";
            return false;
        }
        return false;
    }
    return true;
}

const passConfCheck = () => 
{

    if(!($("input[name='su-password-confirm'").val() == $("input[name='su-password'").val())){
        message = "Passwords do not match";
        return false;
    }
        return true;
}

