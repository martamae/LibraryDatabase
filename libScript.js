//LOGON FUNCTIONS
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

//PATRON AND LIBRARIAN PROFILE FUNCTIONS
var newCardForm = function () {
    //Creates form to replace library card

    //Delete change button
    var replace = document.getElementById("replaceButton");
    replace.parentNode.removeChild(replace);

    //Get element to put form into
    var parent = document.getElementById("newCard");

    //Generate form
    //Create text input box for Password
    var newPrompt = document.createElement("p");
    newPrompt.className = "inProfileInput";
    newPrompt.innerHTML = "Enter your pin: ";

    var newInput = document.createElement("input");
    newInput.type = "password";
    newInput.className = "inProfileInput";
    newInput.id = "newip";

    //Create get new button
    var replaceButton = document.createElement('input');
    replaceButton.type = "button";
    replaceButton.value = "Replace";
    replaceButton.className = "inProfile";
    replaceButton.onclick = function () { pinValidReplace() };

    //Cancel Button
    var cancelButton = document.createElement('input');
    cancelButton.type = "button";
    cancelButton.className = "inProfile";
    cancelButton.value = "Cancel";
    cancelButton.onclick = function () { cancel() };

    //Create form element
    var replaceForm = document.createElement('form');

    //Append elements of form to the form
    replaceForm.appendChild(newPrompt);
    replaceForm.appendChild(newInput);
    replaceForm.appendChild(replaceButton);
    replaceForm.appendChild(cancelButton);

    //Append the form to the parent div
    parent.appendChild(replaceForm);
}

var pinValidReplace = function () {
    //Checks password for replace library card
    //If pin is valid the library card is replace

    var pin = document.getElementById("newip").value;

    //Check pin value entered for delete in php
    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "replace=set&pin=" + pin;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var newNum = req.responseText;

            if (newNum == false) {
                //If the users pin input is not valid
                var message = document.getElementById("cardMessage");
                message.innerHTML = "Invalid pin entered";
            }
            else {
                //If pin was valid print new card number
                alert("Your library card has been replace\n You new card number is: " + newNum);

                window.location.href = "patronProfile.php";
            }
        }
    };

    req.open('POST', 'checkPW.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("cardMessage");
    message.innerHTML = "Processing...";
}

var pwForm = function () {
    //Creates form to update password

    //Delete change button
    var change = document.getElementById("change");
    change.parentNode.removeChild(change);

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
    var changeButton = document.createElement('input');
    changeButton.type = "button";
    changeButton.value = "Change";
    changeButton.className = "inProfile";
    changeButton.onclick = function () { checkPassword() };

    //Cancel Button
    var cancelButton = document.createElement('input');
    cancelButton.type = "button";
    cancelButton.className = "inProfile";
    cancelButton.value = "Cancel";
    cancelButton.onclick = function () { cancel() };

    //Create form element
    var changeForm = document.createElement('form');

    //Append elements of form to the form
    changeForm.appendChild(pwPrompt);
    changeForm.appendChild(pwInput);
    changeForm.appendChild(newPWPrompt);
    changeForm.appendChild(newPWInput);
    changeForm.appendChild(changeButton);
    changeForm.appendChild(cancelButton);

    //Append the form to the parent div
    parent.appendChild(changeForm);
}

var checkPassword = function () {
    //Checks old and new password input for password update
    //If input is valid password is changed

    var pw = document.getElementById("pw").value;
    var newPW = document.getElementById("newPW").value;

    //Check pw value in php
    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "pwchange=set&pw=" + pw + "&new=" + newPW;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var correct = req.responseText;

            if (correct == "length") {
                //If the new input is not between 4-6 chars
                var message = document.getElementById("pwMessage");
                message.innerHTML = "Pin needs to be 4 characters long";
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

                location.reload();
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

var cancel = function () {
    //If cancel button is clicked 
    location.reload();
}

var deleteForm = function () {
    //Creates form to delete account

    //Delete delete button
    var del = document.getElementById("deleteButton");
    del.parentNode.removeChild(del);

    //Get element to put form into
    var parent = document.getElementById("delete");

    //Generate form
    //Create text input box pw
    var pin = document.createElement("p");
    pin.className = "inProfileInput";
    pin.innerHTML = "Enter your pin number: ";

    var pInput = document.createElement("input");
    pInput.type = "password";
    pInput.className = "inProfileInput";
    pInput.id = "pinDel";

    //Create delete button
    var delButton = document.createElement('input');
    delButton.type = "button";
    delButton.className = "inProfile";
    delButton.value = "Delete";
    delButton.onclick = function () { checkPin() };

    //Cancel Button
    var cancelButton = document.createElement('input');
    cancelButton.type = "button";
    cancelButton.className = "inProfile";
    cancelButton.value = "Cancel";
    cancelButton.onclick = function () { cancel() };

    //Create form element
    var deleteForm = document.createElement('form');

    //Append elements of form to the form
    deleteForm.appendChild(pin);
    deleteForm.appendChild(pInput);
    deleteForm.appendChild(delButton);
    deleteForm.appendChild(cancelButton);

    //Append the form to the parent div
    parent.appendChild(deleteForm);
}

var checkPin = function () {
    //Checks pin input for account delete
    //If the pin input is valid user confirms they want to delete account

    var pin = document.getElementById("pinDel").value;

    //Check pin value entered for delete in php
    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "deleteCheck=set&pin=" + pin;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var correct = req.responseText;

            if (correct == "out") {
                var message = document.getElementById("dMessage");
                message.innerHTML = "You cannot delete your account if you have books checked out";
            }
            else if (correct == "req") {
                var message = document.getElementById("dMessage");
                message.innerHTML = "You cannot delete your account if you have books requested";
            }
            else if (correct == true) {
                //confirm the user wantes to delete their account
                var yes = confirm("Are you sure you want to delete you account?");

                if (yes) {
                    deleteAccount();
                }
                else {
                    cancel();
                }
            }
            else {
                //If the users pin input is not valid
                var message = document.getElementById("dMessage");
                message.innerHTML = "Invalid pin entered";
            }
        }
    };

    req.open('POST', 'checkPW.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("dMessage");
    message.innerHTML = "Processing...";
}

var deleteAccount = function () {
    //Deletes users account

    var pin = document.getElementById("pinDel").value;

    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "delete=set&pin=" + pin;

    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            alert("Your account has been deleted");

            window.location.href = "libraryHome.php";
        }
    };

    req.open('POST', 'checkPW.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("dMessage");
    message.innerHTML = "Processing...";
}

//PATRON JOIN FUNCTIONS
var validateJoin = function () {
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;

    //Validates that the user entered a fname and lname
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
        validateDate();
    }
}

var validateDate = function () {
    var date = document.getElementById("DOB").value;
    
    //Validate that the user entered a date
    if (date == "") {
        var message = document.getElementById("jMessage");
        message.innerHTML = "Date field cannot be empty";
    }
    else {
        validatePin();
    }
}

var validatePin = function () {
    //Validates pin input for patron join

    var pinVal = document.getElementById('pinNum').value;
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;
    var DOB = document.getElementById('DOB').value;

    //validate the user entered a pin
    if (pinVal == "") {
        var message = document.getElementById("jMessage");
        message.innerHTML = "Pin number field cannot be empty";
    }
    else {
        var req = new XMLHttpRequest();

        if (!req) {
            throw 'Unable to create HttpRequest.';
        }

        var variablesToSend = "join=set&pinNum=" + pinVal + "&fname=" + fname + "&lname=" + lname + "&DOB=" + DOB;

        req.onreadystatechange = function () {
            if (this.readyState === 4 && req.status === 200) {
                var cardNum = req.responseText;

                //Validate the pin is right length, not
                //non-numeric and is unique
                if (cardNum == "length") {
                    //If the input is not 4 char
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin needs to be 4 characters long";
                }
                else if (cardNum == "unique") {
                    //If the input is not unique
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin is not unique";
                }
                else if (cardNum == "num") {
                    //if input is not a number
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Pin must be a number";
                }
                else if (cardNum == false) {
                    //If the users pw input is not valid
                    var message = document.getElementById("jMessage");
                    message.innerHTML = "Problem creating account. Try again";
                }
                else {
                    alert("Welcome to the library!\n Your library card number is: " + cardNum);

                    window.location.href = "patronHome.php";
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

//LIBRARIAN HOME FUNCTIONS
var deleteBook = function (button) {
    //Librarian deletes book from library
    var bookId = button.value;
    var bookName = button.name;

    //confirms the user wants to delete the book
    var sure = confirm("Are you sure you want to delete " + bookName + " from the library?");

    //If the user confirms delete book
    if (sure) {
        //Send request to php
        var req = new XMLHttpRequest();

        if (!req) {
            throw 'Unable to create HttpRequest.';
        }

        var variablesToSend = "deleteBook=" + bookId;
        req.onreadystatechange = function () {
            if (this.readyState === 4 && req.status === 200) {
                var deleted = req.responseText;



                if (deleted == "out") {
                    alert("You must wait until this book is returned to delete it");
                }
                else if (deleted == "request") {
                    alert("You must wait until this book is not requested to delete it");
                }
                else if (deleted == true) {
                    //When book has been deleted print success message
                    alert(bookName + " has been deleted from the library");

                    location.reload();
                }
            }
        };

        req.open('POST', 'addBookShelf.php', true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send(variablesToSend);

    }
}

//ADD BOOK FUNCTIONS
var addNewGenre = function () {
    //New input field for user to enter custom genre

    //Adds input field to add book page to get genre
    var selectList = document.getElementById("selectGenre");

    //Delete select list
    selectList.parentNode.removeChild(selectList);

    //get input field
    var genreInput = document.getElementById("addGenreText");
    genreInput.type = "text";

    //Get shelf input
    var shelf = document.getElementById("shelf");
    var shelftxt = document.getElementById("shelftxt");
    shelf.hidden = false;
    shelftxt.hidden = false;

    //change onclick
    var button = document.getElementById("addGenButton");
    button.onclick = function () { addValidateNewGenre() };
}

var addValidateNewGenre = function () {
    //Validate that elements are not empty when 
    //new genre is entered

    //Get elements
    var title = document.getElementById("title").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var genre = document.getElementById("addGenreText").value;

    //Check that elements are not empty
    if(genre == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Genre field cannot be left empty";
    }
    else if (title == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Title field cannot be left empty";
    }
    else if (fname == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Author name field cannot be left empty";
    }
    else if (lname == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Author name field cannot be left empty";
    }
    else {
        addBookNewGen();
    }
}

var addValidate = function () {
    //Validate that elements are not empty when genre is selected

    //Get elements
    var title = document.getElementById("title").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;

    //Check that elements are not empty
    if (title == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Title field cannot be left empty";
    }
    else if (fname == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Author name field cannot be left empty";
    }
    else if (lname == "") {
        var message = document.getElementById("addMessage");
        message.innerHTML = "Author name field cannot be left empty";
    }
    else {
        addBook();
    }
}

var addBook = function () {
    //Get elements to validate and add
    var title = document.getElementById("title").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var genre = document.getElementById("genreChoice").value;
    var description = document.getElementById("description").value;

    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "addBook=set&title=" + title + "&fname=" + fname + "&lname=" + lname + "&genre=" + genre + "&description=" + description;
    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var added = req.responseText;
            console.log(added);
            if (added == "title") {
                var message = document.getElementById("addMessage");
                message.innerHTML = "Title must be unique";
            }
            else if (added == true) {
                //When book has been deleted print success message
                alert(title + " has been added from the library");

                window.location.href = "librarianHome.php";
            }
            else {
                var message = document.getElementById("addMessage");
                message.innerHTML = "Problem adding book try again";
            }
        }
    };

    req.open('POST', 'addBookShelf.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("addMessage");
    message.innerHTML = "Processing...";
}


var addBookNewGen = function() {
    //Get elements to validate and add
    var title = document.getElementById("title").value;
    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var genre = document.getElementById("addGenreText").value;
    var description = document.getElementById("description").value;
    var shelf = document.getElementById("shelf").value;

    var req = new XMLHttpRequest();

    if (!req) {
        throw 'Unable to create HttpRequest.';
    }

    var variablesToSend = "addBookNewGen=set&title=" + title + "&fname=" + fname + "&lname=" + lname + "&genre=" + genre + "&description=" + description+"&shelf="+shelf;
    req.onreadystatechange = function () {
        if (this.readyState === 4 && req.status === 200) {
            var added = req.responseText;
            console.log(added);
            if (added == "title") {
                var message = document.getElementById("addMessage");
                message.innerHTML = "Title must be unique";
            }
            else {
                //When book has been deleted print success message
                alert(title + " has been added from the library");

                window.location.href = "librarianHome.php";
            }
        }
    };

    req.open('POST', 'addBookShelf.php', true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send(variablesToSend);

    var message = document.getElementById("addMessage");
    message.innerHTML = "Processing...";
}

//LIBRARIAN SHELF FUNCTION
var removeShelf = function (button) {
    //Librarian deletes shelf from library
    var id = button.value;

    //confirms the user wants to delete the book
    var sure = confirm("Are you sure you want to remove this shelf?");

    //If the user confirms delete shelf
    if (sure) {
        //Send request to php
        var req = new XMLHttpRequest();

        if (!req) {
            throw 'Unable to create HttpRequest.';
        }

        var variablesToSend = "removeShelf=" + id;
        req.onreadystatechange = function () {
            if (this.readyState === 4 && req.status === 200) {
                var rm = req.responseText;

                if (rm == "full") {
                    alert("You  cannot delete a shelf with books on it");
                }
                else if (rm == true) {
                    //When book has been deleted print success message
                    alert("The shelf has been removed from the library");

                    location.reload();
                }
            }
        };

        req.open('POST', 'addBookShelf.php', true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send(variablesToSend);
    }
}

//CREATE LIBRARIAN FUNCTIONS
var validateCreate = function () {
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;

    //Validates that the user entered a fname and lname
    if (fname == "") {
        var message = document.getElementById("createMessage");
        message.innerHTML = "Name field cannot be empty";
    }
    else if (lname == "") {
        var message = document.getElementById("createMessage");
        message.innerHTML = "Name field cannot be empty";
    }
    else {
        var message = document.getElementById("createMessage");
        validateCreateDate();
    }
}

var validateCreateDate = function () {
    var date = document.getElementById("DOB").value;

    //Validate that the user entered a date
    if (date == "") {
        var message = document.getElementById("createMessage");
        message.innerHTML = "Date field cannot be empty";
    }
    else {
        validateUNPin();
    }
}

var validateUNPin = function () {
    //Validates pin input for patron join

    var pinVal = document.getElementById('pinNum').value;
    var username = document.getElementById('username').value;
    var fname = document.getElementById('fname').value;
    var lname = document.getElementById('lname').value;
    var DOB = document.getElementById('DOB').value;

    //validate the user entered a pin
    if (pinVal == "") {
        var message = document.getElementById("createMessage");
        message.innerHTML = "Pin number field cannot be empty";
    }
    else if (username == "") {
        var message = document.getElementById("createMessage");
        message.innerHTML = "Username field cannot be empty";
    }
    else {
        var req = new XMLHttpRequest();

        if (!req) {
            throw 'Unable to create HttpRequest.';
        }

        var variablesToSend = "create=set&pinNum=" + pinVal + "&fname=" + fname + "&lname=" + lname + "&DOB=" + DOB + "&username=" + username;

        req.onreadystatechange = function () {
            if (this.readyState === 4 && req.status === 200) {
                var created = req.responseText;

                //Validate the pin is right length, not
                //non-numeric and is unique
                if (created == "length") {
                    //If the pin input is not 4 char
                    var message = document.getElementById("createMessage");
                    message.innerHTML = "Pin needs to be 4 characters long";
                }
                else if (created == "unique") {
                    //If the pin input is not unique
                    var message = document.getElementById("createMessage");
                    message.innerHTML = "Pin is not unique";
                }
                else if (created == "uniqueUN") {
                    //If the username input is not unique
                    var message = document.getElementById("createMessage");
                    message.innerHTML = "Username is not unique";
                }
                else if (created == "num") {
                    //if pin input is not a number
                    var message = document.getElementById("createMessage");
                    message.innerHTML = "Pin must be a number";
                }
                else if (created == false) {
                    //If the users pw input is not valid
                    var message = document.getElementById("createMessage");
                    message.innerHTML = "Problem creating account. Try again";
                }
                else {
                    alert("Welcome to the library!");

                    window.location.href = "librarianHome.php";
                }
            }
        };

        req.open('POST', 'joinValidate.php', true);
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.send(variablesToSend);

        var message = document.getElementById("createMessage");
        message.innerHTML = "Processing...";
    }
}
    