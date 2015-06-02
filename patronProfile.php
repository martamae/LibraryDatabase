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

    //If the return button is clicked
    if(isset($_POST['return'])){
        $mysqli->query("UPDATE Book SET checkedOutBy=NULL, dueDate=NULL, dateOut=NULL WHERE id='".$_POST['return']."'");
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
    <title>Patron Profile</title>
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

    <!--Button to return to home page-->
    <div class="profileLink">
        <a href="patronHome.php"><input type="button" class="profileButton" value="Home"></a>
    </div>

    <!-- Button to logout -->
    <div class="logout">
        <a href="patronHome.php?action=logout"><input type="button" class="logoutButton" value="Logout"></a>
    </div>
</div>

<div class="pProfile">
    <?php
        //Get info to print
        $personInfo = $mysqli->query("SELECT Person.id, fname, lname, libraryCardNum, Person.pinNum, libraryCard.dateIssued AS dateIssue FROM Person
                                      INNER JOIN libraryCard ON libraryCard.id = Person.libraryCardNum
                                      WHERE libraryCardNum='".$_SESSION['cardNum']."'");
        $profileInfo = $personInfo->fetch_assoc();
        echo "Name: " .$profileInfo['fname']. " " .$profileInfo['lname']. "<br>";
        echo "Library Card Number: " .$profileInfo['libraryCardNum']. "<br>";
        echo "Date Issued: " .$profileInfo['dateIssue'];
    ?>
    <div id="pwCheck" class="profButton">
        <input type="button" class="inProfile" value="Change Password" id="replace" onclick="pwForm()">
        <p id="pwMessage" class="message"></p>
    </div>
    <div id="delete" class="profButton">
        <input type="button" class="inProfile" value="Delete Account" id="deleteButton" onclick="deleteForm()">
        <p id="dMessage" class="message"></p>
    </div>

    <?php
        echo "Books Checked Out:<br>";

        //Get info to print checked out books
        $outInfo = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, dueDate, dateOut FROM Book WHERE checkedOutBy =(SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."')");

        if (mysqli_num_rows($outInfo) == 0){
            echo '<p class="error">You currently have no books checked out<br><br></p>';
        }
        else {
            //Print table of books from database
            echo "<table>";
            echo "<tbody>";
            echo "<tr><th>Title</th><th>Author Name</th><th>Date Checked Out</th><th>Due Date</th><th>Return</th></tr>";

            while ($row = $outInfo->fetch_assoc()) {
                echo "<tr> <td>" . $row['title'] . "<td>" . $row['authorFName'] . " " . $row['authorLName'] . "<td>" . $row['dateOut'] . "<td>" . $row['dueDate'];

                echo "<td>";
                //Return button
                echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="return">Return</button></form>';
            }

            echo "</tbody>";
            echo "</table>";
        }

         echo "Books Requested:<br>";

        $reqInfo = $mysqli->query("SELECT Book.id AS id, authorFName, authorLName, title, dueDate, checkedOutBy, request.id AS reqId FROM Book
                                  INNER JOIN request ON request.bid = Book.id
                                  WHERE pid = ( SELECT id FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."')");

        //Get number of requests
        $numRequests = mysqli_num_rows($reqInfo);

        if ($numRequests == 0) {
            echo '<p class="error">You currently have no books requested</p>';
        }
        else {
            //Print table of books from database
            echo "<table>";
            echo "<tbody>";
            echo "<tr><th>Title</th><th>Author Name</th><th>Status</th></tr>";

            while ($row = $reqInfo->fetch_assoc()) {
                //Get number request for each book
                $bReqNum = $mysqli->query("SELECT id FROM request WHERE bid='".$row['id']."'");
                $bNumRequests = mysqli_num_rows($bReqNum);
                //Get first request
                $requestMin = $mysqli->query("SELECT MIN(id), pid FROM request WHERE bid='" . $row['id'] . "'");
                $first = $requestMin->fetch_assoc();

                echo "<tr> <td>" . $row['title'] . "<td>" . $row['authorFName'] . " " . $row['authorLName'];

                echo "<td>";

                //Request Status
                if ($row['checkedOutBy'] == NULL) {
                    //If the books is not checked out
                    if ($profileInfo['id'] == $first['pid']) {
                        //If the book is checked in and first request is user
                        //Print check out button
                        echo "Requested book available";
                        echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="reqCheckOut">Check Out</button></form>';
                    } else {
                        //If the book is requested and user is not first in line
                        echo "On hold<br>";
                        echo "<br> Number of requests: " . $bNumRequests;

                        //Print request/cancel request buttons
                        echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="cancel">Cancel Request</button></form>';
                    }
                } else {
                    //If book is checked out print due date
                    echo "Checked Out <br>";
                    echo "Due date:<br>" . $row['dueDate'];
                    echo "<br><br> Number of requests: " . $bNumRequests;

                    //Print Cancel Return button
                    echo '<form method="POST"><button type="submit" class="tableButton" value="' . $row['id'] . '" name="cancel">Cancel Request</button></form>';
                }
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