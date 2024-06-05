$(document).ready(function () {
    var email = window.localStorage.getItem("email");
    
    if (email === null || email === undefined) {
        window.location.replace("login.html")
    }
    else {
        window.location.replace("profile.html")
    }
})  