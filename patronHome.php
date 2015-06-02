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

    //If the check out button is clicked
    if(isset($_POST['checkOut'])){
        $mysqli->query("UPDATE Book SET checkedOutBy=(SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'), dateOut=curdate(), dueDate=date_add(curdate(), INTERVAL 10 DAY)
                        WHERE id='".$_POST['checkOut']."'");
    }

    //If the return button is clicked
    if(isset($_POST['return'])){
        $mysqli->query("UPDATE Book SET checkedOutBy=NULL, dueDate=NULL, dateOut=NULL WHERE id='".$_POST['return']."'");
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
        <form class="searchForm" method="GET">
            <input type="text" class="searchInput" name="searchInput">
            <select name="searchParameter" required class="searchInput">
                <option selected disabled>Search Categories</option>
                <option value="authorFName">Author First Name</option>
                <option value="authorLName">Author Last Name</option>
                <option value="title">Title</option>
                <option value="genre">Genre</option>
                <option value="description">Description</option>
            </select>
            <input type="submit"class="searchButton" value="Search Library" name="search">
        </form>
    </div>
</div>

<div>
    <table>
        <tbody>
        <tr><th>Title</th><th>Author Name</th><th>Description</th><th>Genre</th><th>Location</th><th>Check Out</th></tr>
        <?php
            //When the user has searched the library
            if(isset($_GET['searchParameter'])){
                $searchVar = $_GET['searchInput'];

                switch($_GET['searchParameter']) {
                    case authorFName:
                        $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy WHERE authorfName LIKE '%".$searchVar."%'");
                        break;
                    case authorLName:
                        $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy WHERE authorlName LIKE '%".$searchVar."%'");
                        break;
                    case title:
                        $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy WHERE title LIKE '%".$searchVar."%'");
                        break;
                    case genre:
                        $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy WHERE genre LIKE '%".$searchVar."%'");
                        break;
                    case description:
                        $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy WHERE description LIKE '%".$searchVar."%'");
                        break;
                }
            }
            else { //When the user has not searched -- print all
                $books = $mysqli->query("SELECT Book.id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.libraryCardNum AS libNum FROM Book
                                        INNER JOIN Shelf ON Shelf.id = Book.shelf
                                        LEFT JOIN Person on Person.id = Book.checkedOutBy");
            }

            if (mysqli_num_rows($books) == 0){
                echo '<p class="error">There are no books in the library matching that description</p>';
            }
            else {
                //Print table of books from database
                while ($row = $books->fetch_assoc()) {
                    echo "<tr> <td>" . $row['title'] . "<td>" . $row['authorFName'] . " " . $row['authorLName'] . "<td>" . $row['description'] . "<td>" . $row['genre'];

                    echo "<td> Floor " . $row['floorNum'] . "<br>" . $row['location'];

                    echo "<td>";

                    //Check out row
                    if ($row['checkedOutBy'] == NULL) {
                        //If not checked out print check out button
                        echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="checkOut">Check Out</button></form>';
                    } else if ($row['libNum'] == $_SESSION['cardNum']) {
                        //If checked out by current user print return button
                        echo "Due date:<br>" . $row['dueDate'];
                        echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="return">Return</button></form>';
                    } else {
                        //If check out by someone else print request button and due date
                        echo "Checked Out <br>";
                        echo "Due date:<br>" . $row['dueDate'];
                        echo 'Number of requests';
                        echo "Request Button";
                    }
                }
            }
        ?>
        </tbody>
    </table>

    <?php
        if (isset($_GET['searchParameter'])) {
            //Button to see all books if user has searched
            echo '<a href="patronHome.php"><input type="button" class="seeAll" value="See All Books"></a>';
        }
    ?>

</div>

</body>
</html>

