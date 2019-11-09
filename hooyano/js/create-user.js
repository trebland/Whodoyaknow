function createCookie(key, value) {
    let cookie = escape(key) + "=" + escape(value) + ";";
    document.cookie = cookie;
    console.log(cookie);
    console.log("Creating new cookie with key: " + key + " value: " + value);
}

if (document.readyState == 'loading')
{
    document.addEventListener('DOMContentLoaded', ready)
}
else
{
    ready()
}

function ready()
{
    document.getElementsByClassName('submitbutton')[0].addEventListener('click', submitClicked)
}
function submitClicked()
{
    var psw1 = document.getElementById("password1").value
    var psw2 = document.getElementById("password2").value

    if (psw1 != psw2) 
    { 
        alert("\nPasswords did not match. Please try again.") 
        return;
    }
    
    createUser();
}

function createUser()
{
    var username = document.getElementById("username").value;
    var fullname = document.getElementById("full_name").value;
    var password = document.getElementById("password1").value;

    console.log(username);
    console.log(fullname);
    console.log(password);

    var isValidUsername = /^[a-zA-Z0-9]+$/.test(username);
    // Name does not contain certain things
    if(!(isValidUsername))
    {
        alert("Username is not valid! Please only use letters and numbers.");
        return;
    }

    var isValidName = /^[a-zA-Z ]+$/.test(fullname);
    // Name does not contain certain things
    if(!(isValidName))
    {
        alert("Name is not valid! Please only use letters.");
        return;
    }

    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "api/createUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(json.message);
            if(json.status == 1 || json.status == 2)
            {
                alert(json.message);
                return;
            }
            location.replace("loginscreen.html");
        }
        else {
            console.log('error')
        }
    };
    var data = JSON.stringify({"username": username, "password": password, "full_name": fullname});
    xhr.send(data);
}