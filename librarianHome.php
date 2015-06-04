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
        <a href="librarianProfile.php"><input type="button" class="profileButton" value="Profile"></a>
    </div>

    <!-- Button to add book -->
    <div class="profileLink">
        <a href="addBook.php"><input type="button" class="profileButton" value="Add Book"></a>
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <a href="librarianHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>

    <!--button to search inventory-->
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
            <input type="submit" class="searchButton" value="Search Inventory" name="search">
        </form>
    </div>
</div>

<div>
    <table>
        <tbody>
        <tr><th>Title</th><th>Author Name</th><th>Description</th><th>Genre</th><th>Location</th><th>Status</th><th>Added By</th><th>Delete</th></tr>
        <?php
        //When the user has searched the inventory
        if(isset($_GET['searchParameter'])){
            $searchVar = $_GET['searchInput'];

            switch($_GET['searchParameter']) {
                case authorFName:
                    $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedByWHERE authorfName LIKE '%".$searchVar."%'");
                    break;
                case authorLName:
                    $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedBy WHERE authorlName LIKE '%".$searchVar."%'");
                    break;
                case title:
                    $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedByWHERE title LIKE '%".$searchVar."%'");
                    break;
                case genre:
                    $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedBy WHERE genre LIKE '%".$searchVar."%'");
                    break;
                case description:
                    $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedBy WHERE description LIKE '%".$searchVar."%'");
                    break;
            }
        }
        else { //When the user has not searched -- select all
            $books = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, genre, description, checkedOutBy, dueDate, dateOut, Shelf.location AS location, Shelf.floorNum AS floorNum, Person.fName AS pfname, Person.lname AS plname, Librarian.fname AS lfname, Librarian.lname AS llname
                                      FROM Book
                                      INNER JOIN Shelf ON Shelf.id = Book.shelf
                                      LEFT JOIN Person ON Person.id = Book.checkedOutBy
                                      INNER JOIN Librarian ON Librarian.id = Book.addedBy");
        }

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

    <?php
    if (isset($_GET['searchParameter'])) {
        //Button to see all books if user has searched
        echo '<a href="librarianHome.php"><input type="button" class="seeAll" value="See All Books"></a>';
    }
    ?>

</div>

</body>
</html>

<?php
    $mysqli->close();
?>
