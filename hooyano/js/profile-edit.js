var urlbase;

function VerifyNameSubmission()
{
    var name;

    // Name does not contain certain things
    if(foobar)
    {
        // Send user id with name change request
    }

}

function VerifyPasswordSubmission()
{
    var oldPassword;
    var newPassword;
    var confirmPassword;

    // if oldPassword matches to the user id
    // .. PROCEED
    if (newPassword == confirmPassword)
    {
        // Change Password
    }
}

function SubmitEdit()
{ 
    xhr = new XMLHttpRequest();
    var url = "api/editUser.php";
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.onreadystatechange = function () { 
        if (xhr.readyState == 4 && xhr.status == 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(json.email + ", " + json.name);
            if(json.status != 0)
            {
                alert(json.message);
                return;
            }
        }
    }

    var name = document.getElementById();
    var password = document.getElementById();

    var data = 
    JSON.stringify(
    {
    "name":"Thomas Mitchborn",
    "password":"LARACROFT"
    });

    xhr.send(data);
}

function SubmitName()
{

}

function SubmitPassword()
{
    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "https://projectrepository.info/hooyano/api/loginUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            if(json.status != 0)
            {
                alert(json.message);
                return;
            }
            createCookie("user_id", json.userid);
            location.replace("contact-page.html");
        }
        else {
            console.log('error')
        }
    };
    var data = JSON.stringify({"password": password});
    xhr.send(data);
}