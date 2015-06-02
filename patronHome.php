<?php
    include "secret.php";

    session_start();

    //destroy data in session array and redirects to login page
    if ($_GET['action'] == 'logout') {
        $_SESSION = array();
        session_destroy();
        header("Location: libraryHome.php", true);
        die();
    }


    if(session_status() == PHP_SESSION_ACTIVE) {
      if (!isset($_SESSION['cardNum'])) {
          //If a patron is logging on save cardNum in session storage
          if (isset($_POST['cardNum'])) {
              $_SESSION['cardNum'] = $_POST['cardNum'];
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
    <title>Patron Homepage</title>
</head>
<body class="phome">
<!-- header of page -->
<div class="pheader">

    <!-- Display the users profile info -->
    <div class="profileInfo">
        <p>
            <!--Print users name-->
            <?php
                 $names = $mysqli->query("SELECT fname, lname FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'");

                $name = $names->fetch_assoc();

                echo $name['fname'];
                echo " ";
                echo $name['lname'];
            ?>
        </p>
        <p>CARD #:
            <!-- Print users card number -->
            <?php
                echo $_SESSION['cardNum'];
            ?>
        </p>
    </div>

    <!-- Link to the users profile page -->
    <div class="profileLink">
        <input type="submit" class="profileButton" value="Profile" onclick="Location.href='patronProfile.php'">
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <input type="submit"class="logoutButton" value="Logout" onclick="location.href='patronHome.php?action=logout'">
    </div>

    <!-- Form to search books -->
    <div class="search">
        <form class="searchForm" action="patronBookInventory" method="GET">
            <input type="text" class="searchInput">
            <select name="searchParameter" required class="searchInput">
                <option selected disabled>Search Categories</option>
                <option value="authorFName">Author First Name</option>
                <option value="authorLName">Author Last Name</option>
                <option value="title">Title</option>
                <option value="genre">Genre</option>
                <option value="description">Description</option>
            </select>
            <input type="submit"class="searchButton" value="Search Library">
        </form>
    </div>
</div>

<div>
    <table>
        <tbody>
        <tr><th>Title</th><th>Author Name</th><th>Description</th><th>Genre</th><th>Location</th><th>Check Out</th></tr>
        <?php
            $books = $mysqli->
        ?>
        </tbody>
    </table>
</div>

</body>
</html>

