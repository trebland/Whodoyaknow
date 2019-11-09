const newLocal = "user_id";
function LogIn()
{
    function createCookie(key, value) {
        let cookie = escape(key) + "=" + escape(value) + ";";
        document.cookie = cookie;
        console.log(cookie);
        console.log("Creating new cookie with key: " + key + " value: " + value);
    }

    // output: username, jwt, full_name, created_date, status, and message
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;

    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "api/loginUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onload = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            console.log(json.message);
            if(json.status != 0)
            {
                alert(json.message);
                return;
            }
            createCookie("jwt", json.jwt);
            createCookie("expireAt", json.expireAt);
            location.replace("contact-page.html");
        }
        else {
            console.log('error')
        }
    };
    var data = JSON.stringify({"username": username, "password": password});
    xhr.send(data);
}