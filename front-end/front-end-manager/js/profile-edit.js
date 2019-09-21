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
    var url = "url";
    var api = "/editUser.aspx"
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/json");
    xhr.onreadystatechange = function () { 
        if (xhr.readyState == 4 && xhr.status == 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(json.email + ", " + json.name)
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