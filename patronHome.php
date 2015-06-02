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

    //If the request book button is clicked
    if(isset($_POST['request'])) {
        $mysqli->query("INSERT INTO request(pid, bid) VALUES ((SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'), '".$_POST['request']."')");
    }

    //If the cancel request button is clicked
    if(isset($_POST['cancel'])) {
        $mysqli->query("DELETE FROM request WHERE pid =(SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."') AND bid='".$_POST['cancel']."'");
    }

    //If the button to check out a requested book is clicked
    if(isset($_POST['reqCheckOut'])){
        //Check the book out
        $mysqli->query("UPDATE Book SET checkedOutBy=(SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'), dateOut=curdate(), dueDate=date_add(curdate(), INTERVAL 10 DAY)
                        WHERE id='".$_POST['reqCheckOut']."'");

        //Delete the request
        $mysqli->query("DELETE FROM request WHERE pid =(SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."') AND bid='".$_POST['reqCheckOut']."'");
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
                    //User id
                    $Person = $mysqli->query("SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'");
                    $pid = $Person->fetch_assoc();
                    //Get request data
                    $requests = $mysqli->query("SELECT pid FROM request WHERE bid='".$row['id']."'");
                    $numRequests = mysqli_num_rows($requests);
                    $requestMin = $mysqli->query("SELECT MIN(pid) FROM request WHERE bid='".$row['id']."'");
                    $first = $requests->fetch_assoc();

                    if ($row['checkedOutBy'] == NULL) {

                        //If not checked out  and no requests print check out button
                        if ($numRequests == 0) {
                            echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="checkOut">Check Out</button></form>';
                        }
                        //If not checked out and user is first in request line
                        else if ($pid['id'] == $first['pid']) {
                            echo "Requested book available";
                            echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="reqCheckOut">Check Out</button></form>';
                        }
                        else {
                            //If the book is requested and user is not first in line
                            echo "On hold<br>";
                            echo "<br> Number of requests: " .$numRequests;

                            //Print request/cancel request buttons
                            $requested = false;
                            while($reqRow = $requests->fetch_assoc()) {
                                if($reqRow['pid'] == $pid['id']) {
                                    $requested = true;
                                }
                            }

                            if($requested == true) {
                                //If the book is request by this user print cancel request button
                                echo '<form method="POST"><button type="submit" class="tableButton" value="'.$row['id'].'" name="cancel">Cancel Request</button></form>';
                            }
                            else {
                                //If the book has not been requested by this user print request button
                                echo '<form method="POST"><button type="submit" class="tableButton" value="'.$row['id'].'" name="request">Request</button></form>';
                            }
                        }
                    } else if ($row['libNum'] == $_SESSION['cardNum']) {
                        //If checked out by current user print return button
                        echo "Due date:<br>" . $row['dueDate'];
                        echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="return">Return</button></form>';
                    } else {
                        //If checked out by someone else print due date and # of requests
                        echo "Checked Out <br>";
                        echo "Due date:<br>" . $row['dueDate'];
                        echo "<br><br> Number of requests: " .$numRequests;

                        $requested = false;
                        //Determine if the book has been requested by this user
                       while($reqRow = $requests->fetch_assoc()) {
                           if($reqRow['pid'] == $pid['id']) {
                               $requested = true;
                           }
                        }

                        if($requested == true) {
                            //If the book is request by this user print cancel request button
                            echo '<form method="POST"><button type="submit" class="tableButton" value="'.$row['id'].'" name="cancel">Cancel Request</button></form>';
                        }
                        else {
                            //If the book has not been requested by this user print request button
                            echo '<form method="POST"><button type="submit" class="tableButton" value="'.$row['id'].'" name="request">Request</button></form>';
                        }
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

