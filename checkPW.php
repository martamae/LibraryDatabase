<?php
    include "secret.php";

    session_start();

    //Connecting to database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo false;
    }

//If a patron is logged in -- for patron functions
if(isset($_SESSION['cardNum'])) {
    //Get pin for user
    $password = $mysqli->query("SELECT pinNum FROM Person WHERE libraryCardNum='" . $_SESSION['cardNum'] . "'");
    $pw = $password->fetch_assoc();


    if (isset($_POST['replace'])) {
        //Check that pin is correct
        if ($_POST['pin'] == $pw['pinNum']) {
            //If pin is correct replace card
            //Get id of user
            $getId = $mysqli->query("SELECT id FROM Person WHERE libraryCardNum='" . $_SESSION['cardNum'] . "'");
            $person = $getId->fetch_assoc();

            //Delete old card
            $mysqli->query("DELETE FROM libraryCard WHERE id='" . $_SESSION['cardNum'] . "'");

            //Create new card
            $mysqli->query("INSERT INTO libraryCard(pinNum, dateIssued) VALUES ('" . $pw['pinNum'] . "', CURDATE())");

            //Update user with new card info
            $mysqli->query("UPDATE Person SET
                            libraryCardNum=(SELECT libraryCard.id FROM libraryCard WHERE pinNum='" . $pw['pinNum'] . "'),
                            pinNum='" . $pw['pinNum'] . "' WHERE Person.id='" . $person['id'] . "'");

            //Update session variable
            $getNum = $mysqli->query("SELECT libraryCard.id FROM libraryCard WHERE pinNum='" . $pw['pinNum'] . "'");
            $num = $getNum->fetch_assoc();

            //Set session variable
            $_SESSION['cardNum'] = $num['id'];

            //echo cardNum
            echo $num['id'];
        } else {
            //if pin is incorrect echo false
            echo false;
        }
    }

    //If the user wants to change pin #
    if (isset($_POST['pwchange'])) {
        //Check uniqueness of new pin
        $unique = true;
        $pins = $mysqli->query("SELECT pinNum FROM Person");
        while ($row = $pins->fetch_assoc()) {
            if ($row['pinNum'] == $_POST['new']) {
                $unique = false;
                break;
            }
        }

        $length = strlen($_POST['new']);

        if ($length != 4) {
            echo "length";
        } else if (!is_numeric($_POST['new'])) {
            echo "num";
        } else if (!$unique) {
            echo "unique";
        } else if ($_POST['pw'] == $pw['pinNum']) {
            //Prepare statement
            if (!($statement = $mysqli->prepare("UPDATE libraryCard SET pinNum=? WHERE pinNum=?"))) {
                echo "new";
            } else {
                if (!($statement->bind_param('ii', $_POST['new'], $_POST['pw']))) {
                    echo "new";
                } else {
                    $statement->execute();
                    echo true;
                }
            }
        } else {
            //If not = to users pin echo false
            echo false;
        }
    }

    //Check that password is correct for account delete
    if (isset($_POST['deleteCheck'])) {
        if ($_POST['pin'] == $pw['pinNum']) {
            //Check that the user has no checked out books
            $booksOut = $mysqli->query("SELECT id FROM Book
                                        WHERE checkedOutBy=(SELECT id FROM Person WHERE pinNum='".$pw['pinNum']."')");
            $outNum = mysqli_num_rows($booksOut);
            if ($outNum != 0) {
                echo "out";
            }
            else {
                //Check that user has no requests
                $requests = $mysqli->query("SELECT id FROM request WHERE pid=(SELECT id FROM Person WHERE pinNum='".$pw['pinNum']."')");
                $reqNum = mysqli_num_rows($requests);

                if($reqNum != 0){
                    echo "req";
                }
                else {
                    echo true;
                }
            }
        }
        else {
            echo false;
        }
    }

    if (isset($_POST['delete'])) {
        //Get pin
        $pin = $_POST['pin'];
        //Select person id
        $id = $mysqli->query("SELECT id FROM Person WHERE pinNum='".$pin."'");
        $delID = $id->fetch_assoc();

        //Delete the library card
        //Prepare statement
        $statement = $mysqli->prepare("DELETE FROM libraryCard WHERE pinNum = ?");

        $statement->bind_param('i', $pin);

        if ($statement->execute()) {
            //Delete the account
            //Prepare statement
            $smt = $mysqli->prepare("DELETE FROM Person WHERE id = ?");

            $smt->bind_param('i', $delID['id']);

            if ($smt->execute()) {
                //If the Person is deleted end session
                $_SESSION = array();
                session_destroy();
                header("Location: libraryHome.php", true);
                die();
            }
        }
    }
}
else if(isset($_SESSION['username'])) {
    //Get pin for user
    $password = $mysqli->query("SELECT pinNum FROM Librarian WHERE username='" . $_SESSION['username'] . "'");
    $pw = $password->fetch_assoc();


    //If the user wants to change pin #
    if (isset($_POST['pwchange'])) {
        //Check uniqueness of new pin
        $unique = true;
        $pins = $mysqli->query("SELECT pinNum FROM Librarian");
        while ($row = $pins->fetch_assoc()) {
            if ($row['pinNum'] == $_POST['new']) {
                $unique = false;
                break;
            }
        }

        $length = strlen($_POST['new']);

        if ($length != 4) {
            echo "length";
        } else if (!is_numeric($_POST['new'])) {
            echo "num";
        } else if (!$unique) {
            echo "unique";
        } else if ($_POST['pw'] == $pw['pinNum']) {
            //Prepare statement
            if (!($statement = $mysqli->prepare("UPDATE Librarian SET pinNum=? WHERE pinNum=?"))) {
                echo "new";
            } else {
                if (!($statement->bind_param('ii', $_POST['new'], $_POST['pw']))) {
                    echo "new";
                } else {
                    $statement->execute();
                    echo true;
                }
            }
        } else {
            //If not = to users pin echo false
            echo false;
        }
    }
}

$mysqli->close();

?>