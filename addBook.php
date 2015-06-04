<?php
    include "secret.php";

    session_start();

    if(session_status() == PHP_SESSION_ACTIVE) {
        if (!isset($_SESSION['username'])) {
            //If a patron is logging on save cardNum in session storage
            if (isset($_POST['username'])) {
                $_SESSION['username'] = $_POST['username'];
            }
            //If no Patron is logging on direct to homepage
            else {
                header("Location: libraryHome.php", true);
            }
        }
    }

    //Connecting to database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src = 'libScript.js'></script>
    <link rel="Stylesheet" href="libStyle.css">
    <title>Librarian Homepage</title>
</head>
<body class="phome">
<!-- header of page -->
<div class="pheader">

    <!-- Display the users profile info -->
    <div class="profileInfo">
        <p>
            <!--Print users name-->
            <?php
            $names = $mysqli->query("SELECT fname, lname FROM Librarian WHERE username='".$_SESSION['username']."'");

            $name = $names->fetch_assoc();

            echo $name['fname'];
            echo " ";
            echo $name['lname'];
            ?>
        </p>
        <p>
            <!-- Print users username -->
            <?php
            echo $_SESSION['username'];
            ?>
        </p>
    </div>

    <!-- Link to the users profile page -->
    <div class="profileLink">
        <a href="librarianProfile.php"><input type="button" class="profileButton" value="Profile"></a>
    </div>

    <!-- Button to add book -->
    <div class="profileLink">
        <a href="librarianHome.php"><input type="button" class="profileButton" value="Inventory"></a>
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <a href="librarianHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>
</div>

<div class="addBookSect">
    <div class="joinText">
        <div class="joinInput"><p class="message" id="addMessage"></p></div>
        <!--Form to add a book-->
        <form class="joinInput" method="POST" >
            <!--Input title and author name-->
            <p>Title:</p>
            <input type="text" name="title" id="title">
            <p>Author First name:</p>
            <input type="text" name="fname" id="fname">
            <p>Author Last name:</p>
            <input type="text" name="lname" id="lname">
            <p>Description:</p>
            <textarea name="description" id="description"></textarea>
            <p>Genre:</p>
            <p id="selectGenre" class="addText"><select name="genre" id="genreChoice">
                <?php
                    //Get list of genres to print
                    $genres = $mysqli->query("SELECT distinct genre FROM Book");

                    while($row = $genres->fetch_assoc()) {
                        echo '<option value="'.$row['genre'].'">'.$row['genre'].'</option>';
                    }
                ?>
            </select>
             <!--Or the user can input own genre-->
            -OR-
            <input type="button" class="inProfile" value="Add New Genre" onclick="addNewGenre()"></p>
            <input id="addGenreText" type="hidden" name="genreTxt">
            <p hidden id="shelftxt">Select shelf for this genre:</p>
            <select hidden name="shelf" id="shelf">
                <?php
                    //Get list of shelves w/locations
                    $shelves = $mysqli->query("SELECT id, location, floorNum FROM Shelf");

                    while ($row = $shelves->fetch_assoc()) {
                        echo '<option value="'.$row['id'].'">'.$row['id'].': Floor '.$row['floorNum'].' '.$row['location'].'</option>';
                    }
                ?>
            </select>

            <br>
            <input type="button" class="bButton" name="addButton" id="addGenButton" value="Add Book" onclick="addValidate()">
        </form>
    </div>
</div>

</body>
</html>