/* ======== Verify Login ======== */

var message = "";

$(() => {


    console.log("loaded");
    
    $("#su-submit").click(function (e) {

        e.preventDefault(); // Prevent Default Submission

        console.log("submit signup");

        if(!nameCheck() || !emailCheck() || !passCheck() || !passConfCheck)
        {
            console.log(message);
            showError(message);
            return false;
        }
        else {
            
            $firstname = $("input[name='su-firstname'").val();
            $lastname = $("input[name='su-lastname'").val();
            $username = $("input[name='su-username'").val();
            $email = $("input[name='su-email'").val();
            $password = $("input[name='su-password'").val();
            $passwordConf = $("input[name='su-password-confirm'").val();

            console.log($firstname);
            console.log($lastname);
            console.log($username);
            console.log($email);
            console.log($password);
            console.log($passwordConf);

            $.ajax({
                type: "POST",
                url: "requests.php",
                "data" : {
                    "request" : "signup",
                    "form" : {
                    "user_firstname" : $firstname,
                    "user_lastname" : $lastname,
                    "user_username" : $username,
                    "user_email" : $email,
                    "user_password" : $password,
                    "user_password_conf" : $passwordConf
                    }
                }
                })
                .done(function(json) {
                    if(json.status == "success")
                    {
                        window.location.href = "home.php";
                    }
                    else {
                        console.log(json);
                        showError(json.message);
                    }
                })
                .fail(function(xhr, status, error) {
                    alert("(signup There was an Error:" );
                    console.log("Error: " + error);
                    console.log("Status: " + status);
                    console.dir(xhr);
            });
        }

    });
});

// ======== Validation Functions ========

function nameCheck() 
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

function usernameCheck() 
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
 
function emailCheck() 
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

function passCheck() 
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

function passConfCheck() 
{

    if(!($("input[name='su-password-confirm'").val() == $("input[name='su-password'").val())){
        message = "Passwords do not match";
        return false;
    }
        return true;
}

