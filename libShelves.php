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

    <!-- Button to shelves page -->
    <div class="profileLink">
        <a href="libShelves.php"><input type="button" class="profileButton" value="Shelves"></a>
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <a href="librarianHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>
</div>

<div class="lheader">
    <!-- Button to add shelf -->
    <div class="profileLink">
        <a href="updateShelf.php"><input type="button" class="lButton" value="Update/Add Shelves"></a>
    </div>
</div>


<div>
    <table>
        <tbody>
            <tr><th>Shelf</th><th>Genres</th><th>Location</th>
            <?php
                $shelves = $mysqli->query("SELECT id FROM shelf ")

            if (mysqli_num_rows($books) == 0){
                echo '<p class="error">There are no books in the inventory matching that description</p>';
            }
            else {
                //Get added by information
                //Print table of books from database
                while ($row = $books->fetch_assoc()) {
                    echo "<tr> <td>" . $row['title'] . "<td>" . $row['authorFName'] . " " . $row['authorLName'] . "<td>" . $row['description'] . "<td>" . $row['genre'];

                    echo "<td> Floor " . $row['floorNum'] . "<br>" . $row['location'];

                    echo "<td>";

                    //Check out row

                    //Get request data
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

                    //Book added by
                    echo "<td>" . $row['lfname'] . " " . $row['llname'];

                    //Delete Button - deletes book
                    echo '<td><button type="button" class="tableButton" value="'.$row['id'].'" name="'.$row['title'].'" onclick="deleteBook(this)">Delete</button>';
                }
            }
            ?>
            </tbody>
        </table>

    </div>

    </body>
    </html>

<?php
$mysqli->close();
?>