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
    document.getElementsByClassName('connectbutton')[0].addEventListener('click', loginClicked)
}
function loginClicked()
{
    var user = document.getElementById("username").value
    console.log(user)

    var psw1 = document.getElementById("password").value
    console.log(psw1)

    var obj = {username:user, password:psw1}
    var login = JSON.stringify(obj)

    console.log(login)

	var xhr = new XMLHttpRequest()
	xhr.open("POST", loginUser.php, false)
    xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8")
    
    try
	{
		xhr.send(jsonPayload)
		
		var jsonObject = JSON.parse(xhr.responseText)
		
		var status = jsonObject.status
		
		if( status > 0 )
		{
			document.getElementById("loginResult").innerHTML = "User/Password combination incorrect"
			return
        }
    }
    catch(err)
	{
		document.getElementById("loginResult").innerHTML = err.message;
	}
    
}