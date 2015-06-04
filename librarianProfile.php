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

    //destroy data in session array and redirects to login page
    if ($_GET['action'] == 'logout') {
        $_SESSION = array();
        session_destroy();
        header("Location: libraryHome.php", true);
        die();
    }

    //Connecting to database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

    //Delete book button clicked
    if(isset($_POST['deleteBook'])) {
        //Chec
        $mysqli->query("DELETE FROM Book WHERE id='".$_POST['deleteBook']."'");
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
        <a href="librarianHome.php"><input type="button" class="profileButton" value="Inventory"></a>
    </div>


    <!-- Button to logout -->
    <div class="logout">
        <a href="librarianHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>
</div>

<div class="pProfile">
    <?php
        //Get user info to print
        $personInfo = $mysqli->query("SELECT Librarian.id, fname, lname, username, startDate, pinNum FROM Librarian
                                      WHERE username='".$_SESSION['username']."'");
        $profileInfo = $personInfo->fetch_assoc();
        echo "Name: " .$profileInfo['fname']. " " .$profileInfo['lname']. "<br>";
        echo "Username: " .$profileInfo['username']. "<br>";
        echo "Date started: " .$profileInfo['startDate']. "<br><br>";
    ?>

    <div id="pwCheck" class="profButton">
        <input type="button" class="inProfile" value="Change Pin" id="change" onclick="pwForm()">
        <p id="pwMessage" class="message"></p>
    </div>


    <?php
    echo "Books You Added:<br>";

    //Get info to print added books
    $addInfo = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedBy
                                      WHERE addedBy=(SELECT id FROM Librarian WHERE username='".$_SESSION['username']."')");

    if (mysqli_num_rows($addInfo) == 0){
        echo '<p class="error">You have not added any books yet<br><br></p>';
    }
    else {
        //Print table of books from database
        echo "<table>";
        echo "<tbody>";
        echo "<tr><th>Title</th><th>Author Name</th><th>Location</th><th>Status</th><th>Delete</th></tr>";

    while ($row = $addInfo->fetch_assoc()) {
        echo "<tr> <td>" . $row['title'] . "<td>" . $row['authorFName'] . " " . $row['authorLName'];

        echo "<td> Floor " . $row['floorNum'] . "<br>" . $row['location'];

        echo "<td>";

        //Get requests data
        $requests = $mysqli->query("SELECT fname, lname FROM Person
                                            LEFT JOIN request ON request.pid = Person.id
                                            WHERE bid='".$row['id']."'");
        $numRequests = mysqli_num_rows($requests);

        //Print status of book
        if ($row['checkedOutBy'] == NULL) {
            //If book is not checked out
            echo "On Shelf <br><br>";
            if ($numRequests == 0) {
                echo "There are no requests <br>";
            }
            else { //Print requests
                echo "Requested by: <br>";
                while ($reqRow = $requests->fetch_assoc()) {
                    echo $reqRow['fname']. " " . $reqRow['lname'] . "<br>";
                }
            }
        }
        else { //If the book is checked out
            //Print checked out by
            echo "Checked<br> out by:<br>" . $row['pfname'] . " " . $row['plname'] . "<br>";
            echo "Date out:<br>" . $row['dateOut'] . "<br>";
            echo "Due date:<br>" . $row['dueDate']. "<br><br>";

            //Print requests
            if ($numRequests == 0) {
                echo "There are no requests <br>";
            }
            else {
                echo "Requested by: <br>";
                while ($reqRow = $requests->fetch_assoc()) {
                    echo $reqRow['fname']. " " . $reqRow['lname'] . "<br>";
                }
            }
        }

        //Delete Button - deletes book
        echo '<td><button type="button" class="tableButton" value="'.$row['id'].'" name="'.$row['title'].'" onclick="deleteBook(this)">Delete</button>';
    }

    echo "</tbody>";
    echo "</table>";
}

?>

</div>

</body>
</html>

<?php
$mysqli->close();
?>
