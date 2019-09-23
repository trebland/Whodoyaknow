function VNS()
{
    var name = document.getElementById("profile-name-input").value;

    var isValidName = /^[a-zA-Z ]+$/.test(name);
    // Name does not contain certain things
    if(isValidName)
    {
        // Send user id with name change request
        SubmitName(name);
    }
    else
    {
        alert("Name is not valid! Please only use letters");
    }

}

function VPS()
{
    var oldPassword = document.getElementById("profile-opw-input").value;
    var newPassword = document.getElementById("profile-pw1-input").value;
    var confirmPassword = document.getElementById("profile-pw2-input").value;

    // if oldPassword matches to the user id
    // .. PROCEED
    if (newPassword == confirmPassword)
    {
        SubmitPassword(oldPassword, newPassword);
    }
    else
    {
        alert("New passwords do not match!");
    }
}

function SubmitName(name)
{
    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "api/editNameUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            if(json.status != 0)
            {
                alert(json.message);
                return;
            }
            createCookie("jwt", json.jwt);
            createCookie("expireAt", json.expireAt);
            location.replace("profile-page.html");
        }
        else {
            console.log('error')
        }
    };

    var data = JSON.stringify({"jwt": readCookie("jwt"), "new_name": name});
    xhr.send(data);
}

function SubmitPassword(oldpass, newpass)
{
    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "api/editPassUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            if(json.status != 0)
            {
                alert(json.message);
                return;
            }
            createCookie("jwt", json.jwt);
            createCookie("expireAt", json.expireAt);
            location.replace("profile-page.html");
        }
        else {
            console.log('error')
        }
    };
    var data = JSON.stringify({"jwt": readCookie("jwt"), "password": oldpass, "new_pass": newpass});
    xhr.send(data);
}


function createCookie(key, value) {
    let cookie = escape(key) + "=" + escape(value) + ";";
    document.cookie = cookie;
    console.log(cookie);
    console.log("Creating new cookie with key: " + key + " value: " + value);
}

function readCookie(name) {
    let key = name + "=";
    let cookies = document.cookie.split(';');
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        while (cookie.charAt(0) === ' ') {
                cookie = cookie.substring(1, cookie.length);
            }
        if (cookie.indexOf(key) === 0) {
                return cookie.substring(key.length, cookie.length);
            }
    }
    return null;
}