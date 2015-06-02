<?php
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
//header of page
<div class="pheader">

    <div class="profileInfo">
        <p>NAME HERE</p>
        <p>CARD #</p>
    </div>

    <div class="profileLink">
        <input type="submit" class="profileButton" value="Profile" onclick="Location.href='patronProfile.php'">
    </div>

    <div class="logout">
        <input type="submit"class="logoutButton" value="Logout" onclick="location.href='patronHome.php?action=logout'">
    </div>

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



</body>
</html>

