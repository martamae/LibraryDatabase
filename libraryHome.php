<?php
    session_start();

    if(isset($_SESSION['cardNum'])) {
        header("Location: patronHome.php");
    }

    if(isset($_SESSION['username'])) {
        header("Location: librarianHome.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src = 'libScript.js'></script>
    <link rel="Stylesheet" href="libStyle.css">
    <title>Library Homepage</title>
</head>
<body class="home">
<div class="header"><h1>WELCOME TO THE LIBRARY</h1></div>
<div class="loginChoice">
    <div class="loginText"><p id="message" class="message"></p></div>
    <h2 class="loginText" id="loginHeader">Log in As:</h2>
    <div>
        <input type="button" value="Patron" onclick="patronLogon()" class="homeButton" id="patronButton">
        <div id="patronLogonForm"></div>
        <p id="patronText"class="loginText"><a href="patronJoin.php">Join</a> the library</p>
        <input type="button" value="Librarian" onclick="librarianLogon()" class="homeButton" id="librarianButton">
        <div id="librarianLogonForm"></div>
        <p id="librarianText" class="loginText"><a href="createLibrarian.php">Create</a> an employee account</p>
    </div>
</div>

</body>
</html>