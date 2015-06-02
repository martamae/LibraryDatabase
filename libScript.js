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
    loginForm.method = "POST";

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

var pwForm = function () {
    //Delete replace button
    var replace = document.getElementById("replace");
    replace.parentNode.removeChild(replace);

    //Get element to put form into
    var parent = document.getElementById("pwCheck");

    //Generate form
    //Create text input box for old PW
    var pwPrompt = document.createElement("p");
    pwPrompt.className = "inProfileInput";
    pwPrompt.innerHTML = "Enter your old pin: ";

    var pwInput = document.createElement("input");
    pwInput.type = "password";
    pwInput.className = "inProfileInput";
    pwInput.id = "pw";

    //Create text input box for new PW
    var newPWPrompt = document.createElement("p");
    newPWPrompt.className = "inProfileInput";
    newPWPrompt.innerHTML = "Enter your new pin: ";

    var newPWInput = document.createElement("input");
    newPWInput.type = "password";
    newPWInput.className = "inProfileInput";
    newPWInput.id = "newPW";

    //Create replace button
    var replaceButton = document.createElement('input');
    replaceButton.type = "button";
    replaceButton.className = "replaceButton";
    replaceButton.value = "Replace";
    replaceButton.className = "inProfile";
    replaceButton.onclick = function () { checkPassword() };

    //Cancel Button
    var cancelButton = document.createElement('input');
    cancelButton.type = "button";
    cancelButton.className = "inProfile";
    cancelButton.value = "Cancel";
    cancelButton.onclick = function () { cancelReplace() };

    //Create form element
    var replaceForm = document.createElement('form');

    //Append elements of form to the form
    replaceForm.appendChild(pwPrompt);
    replaceForm.appendChild(pwInput);
    replaceForm.appendChild(newPWPrompt);
    replaceForm.appendChild(newPWInput);
    replaceForm.appendChild(replaceButton);
    replaceForm.appendChild(cancelButton);

    //Append the form to the parent div
    parent.appendChild(replaceForm);
}

var checkPassword = function () {
    var pw = document.getElementById("pw").value;
    var newPW = document.getElementById("newPW").value;

    //Check pw value in php
    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "pwReplace=set&pw=" + pw + "&new=" + newPW;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var correct = req.responseText;

            if (correct == "length") {
                //If the new input is not between 4-6 chars
                var message = document.getElementById("pwMessage");
                message.innerHTML = "Pin needs to be between 4 and 6 characters";
            }
            else if (correct == "unique") {
                //If the new input is not unique
                var message = document.getElementById("pwMessage");
                message.innerHTML = "Pin is not unique";
            }
            else if (correct == "new") {
                var message = document.getElementById("pwMessage");
                message.innerHTML = "New pin is invalid";
            }
            else if (correct == "num") {
                //if new input is not a number
                var message = document.getElementById("pwMessage");
                message.innerHTML = "Pin must be a number";
            }
            else if (correct == true) {
                alert("Your password was successfully changes");
                
                window.location.href = "patronProfile.php";
                
            }
            else if (correct == false) {
                //If the users pw input is not valid
                var message = document.getElementById("pwMessage");
                message.innerHTML = "Invalid pin entered";
            }
        }
    };

    req.open('POST', 'checkPW.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("pwMessage");
    message.innerHTML = "Processing...";
}

var cancelReplace = function () {
    //If cancel button is clicked when changing pw
    //Return to profile screen
    window.location.href = "patronProfile.php";
}

var validateJoin = function () {
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;

    if (fname == "") {
        var message = document.getElementById("jMessage");
        message.innerHTML = "Name field cannot be empty";
    }
    else if (lname == "") {
        var message = document.getElementById("jMessage");
        message.innerHTML = "Name field cannot be empty";
    }
    else {
        var message = document.getElementById("jMessage");
        validatePin();
    }
}

var validatePin = function () {
    var pinNum = document.getElementById('pinNum');
    var pinVal = document.getElementById('pinNum').value;

    if (pinVal == "") {
        var message = document.getElementById("jMessage");
        message.innerHTML = "Pin number field cannot be empty";
    }
    else {
        var req = new XMLHttpRequest();

        if (!req) {
            throw 'Unable to create HttpRequest.';
        }

        var variablesToSend = "join=set&pinNum=" + pinVal;

        req.onreadystatechange = function () {
            if (this.readyState === 4 && req.status === 200) {
                var correct = req.responseText;

                if (correct == "length") {
                    //If the input is not between 4-6 chars
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin needs to be between 4 and 6 characters";
                }
                else if (correct == "unique") {
                    //If the input is not unique
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin is not unique";
                }
                else if (correct == "num") {
                    //if input is not a number
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin must be a number";
                }
                else if (correct == true) {
                    alert("Your password was successfully changes");

                    // window.location.href = "patronProfile.php";
                }
                else if (correct == false) {
                    //If the users pw input is not valid
                    var message = document.getElementById("pwMessage");
                    message.innerHTML = "Invalid pin entered";
                }
            }
        };

        req.open('POST', 'joinValidate.php', true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send(variablesToSend);

        var message = document.getElementById("jMessage");
        message.innerHTML = "Processing...";
    }
}