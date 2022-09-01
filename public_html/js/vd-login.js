/* ======== Verify Login ======== */
$(() => {
    
    let message = "";

    $("#lg-submit").on("click", (e) => {

        e.preventDefault(); // Prevent Default Submission


        if(!emailCheck() || !passCheck())
        {
            console.log(message);
            showError(message);
            return false;
        }
        else {

            let form = $(".lg-inputs")[0];
            let formData = new FormData(form);
            formData.append("request", "login");

            for (var pair of formData.entries()) {
                console.log(pair[0]+ ', ' + pair[1]); 
            }


            $.ajax({
                type: "POST",
                url: "requests.php",
                processData: false,
                contentType: false,
                cache: false,
                data: formData,
                success: (data) => {
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

    const emailCheck = () => 
    {
        var emailPattern = /^([a-zA-Z0-9àáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð._-]{1,64})+@([a-zA-Z0-9.-]{1,})+(.[a-zA-Z.]{2,})$/;
        if(!emailPattern.test($("input[name='lg-email'").val())){
            return false;
        }
        else {
            return true;
        }
    }

    const passCheck = () => 
    {

        var passLenPattern = /^(?=.{8,})/;
        if(!passLenPattern.test($("input[name='lg-password'").val())){
            return false;
        }
        else {
            return true;
        }
    }

});