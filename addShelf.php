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
    <title>Add Shelf</title>
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

    <!-- Button to Inventory -->
    <div class="profileLink">
        <a href="librarianHome.php"><input type="button" class="profileButton" value="Inventory"></a>
    </div>

    <!-- Button to shelves page -->
    <div class="profileLink">
        <a href="libShelves.php"><input type="button" class="profileButton" value="Shelves"></a>
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <a href="librarianHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>
</div>

<?php
//Adding shelf
if(isset($_POST['location'])){
    //Create new shelf
    if(!($statement = $mysqli->prepare("INSERT INTO Shelf(location, floorNum) VALUES (?, ?)"))) {
        echo '<p class="error">Error adding shelf. Try again</p>';
    }

    if(!($statement->bind_param('si', $_POST['location'], $_POST['floorNum']))) {
        echo '<p class="error">Error adding shelf. Try again</p>';
    }

    if (!($statement->execute())) {
        echo '<p class="error">Error adding shelf. Try again</p>';
    }

    $genres = $mysqli->query("SELECT distinct genre FROM Book");

    while($row = $genres->fetch_assoc() ){
        if (isset($_POST[$row['genre']])) {
            $moveBooks = $mysqli->query("SELECT id FROM Book WHERE genre='".$row['genre']."'");

            while($bookRow = $moveBooks->fetch_assoc()) {
                //Select books with that genre and move to new shelf
                $mysqli->query("UPDATE Book SET shelf=(SELECT max(id) FROM Shelf)
                                WHERE id ='".$bookRow['id']."'");
            }
        }
    }

    echo '<p class="error">Shelf successfully added</p>';
}
?>

<div class="addBookSect">
    <div class="joinText">
        <div class="joinInput"><p class="message" id="addMessage"></p></div>
        <!--Form to add a book-->
        <form name="addShelf" class="joinInput" method="POST" >
           <!--Location and floor number input-->
            <p>Location:</p>
            <select name="location">
                <option value="West Wing">West Wing</option>
                <option value="East Wing">East Wing</option>
                <option value="North Wing">North Wing</option>
                <option value="South Wing">South Wing</option>
            </select>
            <p>Floor Number:</p>
            <select name="floorNum">
                <option value="1">Floor 1</option>
                <option value="2">Floor 2</option>
            </select>
            <p>Genres to move to shelf:</p>
            <?php
                $genres = $mysqli->query("SELECT distinct genre FROM Book");
                while($row = $genres->fetch_assoc()) {
                    echo '<input type=checkbox name="' . $row['genre'].'">  ' . $row['genre'] .'<br>';
                }
            ?>

            <br>
            <input type="submit" class="bButton" name="addShelfButton" value="Add Shelf">
        </form>
    </div>
</div>

</body>
</html>