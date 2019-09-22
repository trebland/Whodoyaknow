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
    var user = document.getElementById("username").value
    console.log(user)

    var psw1 = document.getElementById("password1").value
    console.log(psw1)
    var psw2 = document.getElementById("password2").value
    console.log(psw2)
    if (psw1 != psw2) 
    { 
        alert("\nPasswords did not match. Please try again.") 
        return;
    }

    var name = document.getElementById("full_name").value
    console.log(name)

    var obj = {username:user, password:psw1, full_name:name}
    var newuser = JSON.stringify(obj)

    console.log(newuser)
}