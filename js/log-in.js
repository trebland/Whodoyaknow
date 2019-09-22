function LogIn()
{
    function createCookie(key, value) {
        let cookie = escape(key) + "=" + escape(value) + ";";
        document.cookie = cookie;
        console.log(cookie);
        console.log("Creating new cookie with key: " + key + " value: " + value);
    }
    // Here you would replace the value
    // .. with a variable based on php return
    createCookie("user_id", "101010");

    // output: username, user_id, full_name, created_date, status, and message
    var username = document.getElementById("username");
    var password = document.getElementById("password");

    // Create a request variable and assign a new XMLHttpRequest object to it.
    var xhr = new XMLHttpRequest();
    var url = "https://projectrepository.info/hooyano/api/loginUser.php";

    // Sending and receiving data in JSON format using POST method
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var json = JSON.parse(xhr.responseText);
            createCookie("user_id", json.userid);
            location.replace("contact-page.html");
        }
        else {
            console.log('error')
        }
    };
    var data = JSON.stringify({"username": username, "password": password});
    xhr.send(data);
}