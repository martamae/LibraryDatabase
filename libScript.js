var patronLogon = function () {
    //Generates form to login
    //Change the header instruction
    var loginHeader = document.getElementById("loginHeader");
    loginHeader.innerHTML = "Patron Log in:";

    //Get elements to remove
    var patronText = document.getElementById("patronText");
    var patronButton = document.getElementById("patronButton");
    var librarianText = document.getElementById("librarianText");
    var librarianButton = document.getElementById("librarianButton");

    //Remove elements
    patronText.parentNode.removeChild(patronText);
    librarianText.parentNode.removeChild(librarianText);
    librarianButton.parentNode.removeChild(librarianButton);
    patronButton.parentNode.removeChild(patronButton);

    //Get parent to add elements to
    var parent = document.getElementById("patronLogonForm");

    //Create library card number input
    var cardPara = document.createElement('p');
    cardPara.className = "loginText";
    var cardText = document.createTextNode("Library Card Number: ");

    var cardInput = document.createElement('input');
    cardInput.type = "text";
    cardInput.name = "cardNum";
    cardInput.className = "loginInput";
    cardInput.id = "cardInput";

    cardPara.appendChild(cardText);

    //Create pin number input
    var pinPara = document.createElement('p');
    pinPara.className = "loginText";
    var pinText = document.createTextNode("Pin Number: ");


    var pinInput = document.createElement('input');
    pinInput.type = "password";
    pinInput.name = "pin";
    pinInput.className = "loginInput";
    pinInput.id = "pinInput";


    pinPara.appendChild(pinText);

    //Create log in button
    var loginButton = document.createElement('input');
    loginButton.type = "button";
    loginButton.name = "patronLog";
    loginButton.value = "Log in";
    loginButton.className = "homeButton";
    loginButton.onclick = function () { patronForm() };

    //Create form element
    var loginForm = document.createElement('form');
    loginForm.method = "POST";

    //Append elements of form to the form
    loginForm.appendChild(cardPara);
    loginForm.appendChild(cardInput);
    loginForm.appendChild(pinPara);
    loginForm.appendChild(pinInput);
    loginForm.appendChild(loginButton);

    //Append the form to the parent div
    parent.appendChild(loginForm);
}

var patronForm = function () {
    //Gets variables from parton login form
    var CI = document.getElementById("cardInput").value;
    var PI = document.getElementById("pinInput").value;

    patronToPHP(CI, PI);
}

var patronToPHP = function (cardNum, pin) {
var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "patronLog=set&cardNum="+cardNum+"&pin="+pin;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var valid = req.responseText;

            if (valid == true) {
                //If the users input is valid login
                //send to patron homepage
                var request = new XMLHttpRequest();

                if (!request) {
                    throw 'Unable to create HttpRequest.';
                }

                var variablesToSend = "patronLog=set&cardNum=" + cardNum + "&pin=" + pin;

                request.open('POST', 'patronHome.php', true);
                request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                request.send(variablesToSend);

                //Go to patron homepage
                window.location.href = "patronHome.php";
            }
            else if (valid == false) {
                //If the users input is not valid
                var message = document.getElementById("message");
                message.innerHTML = "Invalid library card number or pin entered";
            }
        }
    };

    req.open('POST', 'processLogin.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("message");
    message.innerHTML = "Processing...";
}

var librarianLogon = function () {
    //Generate form to login
    //Change the header instruction
    var loginHeader = document.getElementById("loginHeader");
    loginHeader.innerHTML = "Librarian Log in:";

    //Get elements to remove
    var patronText = document.getElementById("patronText");
    var patronButton = document.getElementById("patronButton");
    var librarianText = document.getElementById("librarianText");
    var librarianButton = document.getElementById("librarianButton");

    //Remove elements
    patronText.parentNode.removeChild(patronText);
    librarianText.parentNode.removeChild(librarianText);
    librarianButton.parentNode.removeChild(librarianButton);
    patronButton.parentNode.removeChild(patronButton);

    //Get parent to add elements to
    var parent = document.getElementById("librarianLogonForm");

    //Create username input
    var userPara = document.createElement('p');
    userPara.className = "loginText";
    var userText = document.createTextNode("Username: ");

    var userInput = document.createElement('input');
    userInput.type = "text";
    userInput.className = "loginInput";
    userInput.name = "username";
    userInput.id = "userInput";

    userPara.appendChild(userText);

    //Create pin number input
    var pinPara = document.createElement('p');
    pinPara.className = "loginText";
    var pinText = document.createTextNode("Pin Number: ");


    var pinInput = document.createElement('input');
    pinInput.type = "password";
    pinInput.className = "loginInput";
    pinInput.name = "pin";
    pinInput.id = "pinInput";


    pinPara.appendChild(pinText);

    //Create log in button
    var loginButton = document.createElement('input');
    loginButton.type = "button";
    loginButtonName = "librarianLog";
    loginButton.value = "Log in";
    loginButton.className = "homeButton";
    loginButton.onclick = function () { librarianForm() };

    //Create form element
    var loginForm = document.createElement('form');

    //Append elements of form to the form
    loginForm.appendChild(userPara);
    loginForm.appendChild(userInput);
    loginForm.appendChild(pinPara);
    loginForm.appendChild(pinInput);
    loginForm.appendChild(loginButton);

    //Append the form to the parent div
    parent.appendChild(loginForm);
}

var librarianForm = function () {
    //Gets variables from librarian login form
    var UN = document.getElementById("userInput").value;
    var PI = document.getElementById("pinInput").value;

    librarianToPHP(UN, PI);
}

var librarianToPHP = function (username, pin) {
    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "librarianLog=set&username="+username+"&pin="+pin;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var valid = req.responseText;

            if (valid == true) {
                //If the users input is valid login
                //send to librarian homepage
                var request = new XMLHttpRequest();

                if (!request) {
                    throw 'Unable to create HttpRequest.';
                }

                var variablesToSend = "librarianLog=set&username=" + username + "&pin=" + pin;

                req.open('POST', 'librarianHome.php', true);
                req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                req.send(variablesToSend);

                //Go to librarian homepage
                window.location.href = "librarianHome.php";
            }
            else if (valid == false) {
                //If the users input is not valid
                var message = document.getElementById("message");
                message.innerHTML = "Invalid username or pin entered";
            }
        }
    };

    req.open('POST', 'processLogin.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("message");
    message.innerHTML = "Processing...";
}