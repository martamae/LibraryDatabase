<?php
    session_start();

    include "secret.php";

    //Connecting
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

    if(isset($_SESSION['username'])) {
        if(isset($_POST['addBook'])) {
            //Compare title entered to current titles
            $titles = $mysqli->query("SELECT title FROM Book");

            $uniqueTitle = true;
            while($row = $titles->fetch_assoc()) {
                if($_POST['title'] == $row['title']) {
                    //If the title is not unique echo title
                    $uniqueTitle = false;
                }
            }

            if(!$uniqueTitle) {
                echo "title";
            }
           else {
                //Find correct shelf
                $shelves = $mysqli->query("SELECT shelf FROM Book WHERE genre='".$_POST['genre']."'");
                $shelfNum = $shelves->fetch_assoc();

                //Insert book
                if (!($statement = $mysqli->prepare("INSERT INTO Book(authorFName, authorLName, title, genre, description, addedBy, shelf)
                                                    VALUES(?, ?, ?, ?, ?, (SELECT id FROM Librarian WHERE username= ?), ?)"))) {
                    echo false;
                }
                else {
                    if(!($statement->bind_param('ssssssi', $_POST['fname'], $_POST['lname'], $_POST['title'], $_POST['genre'], $_POST['description'], $_SESSION['username'], $shelfNum['shelf']))){
                        echo false;
                    }
                    else {
                        if(!($statement->execute())) {
                            echo false;
                        }
                        else {
                            echo true;
                        }
                    }
                }
            }
        }
        else if(isset($_POST['addBookNewGen'])) {
            //Compare title entered to current titles
            $titles = $mysqli->query("SELECT title FROM Book");

            $uniqueTitle = true;
            while($row = $titles->fetch_assoc()) {
                if($_POST['title'] == $row['title']) {
                    //If the title is not unique echo title
                    $uniqueTitle = false;
                }
            }

            if(!$uniqueTitle) {
                echo "title";
            }
            else {
                //Insert Book
                if (!($statement = $mysqli->prepare("INSERT INTO Book(authorFName, authorLName, title, genre, description, addedBy, shelf)
                                                    VALUES(?, ?, ?, ?, ?, (SELECT id FROM Librarian WHERE username= ?), ?)"))) {
                    echo false;
                }
                else {
                    if(!($statement->bind_param('ssssssi', $_POST['fname'], $_POST['lname'], $_POST['title'], $_POST['genre'], $_POST['description'], $_SESSION['username'], $_POST['shelf']))){
                        echo false;
                    }
                    else {
                        if(!($statement->execute())) {
                            echo false;
                        }
                        else {
                            echo true;
                        }
                    }
                }
            }
        }
        else if(isset($_POST['deleteBook'])) {
            //check that the book is not checked out
            $out = $mysqli->query("SELECT checkedOutBy FROM Book WHERE id='".$_POST['deleteBook']."'");
            $bookOut = $out->fetch_assoc();

            if($bookOut['checkedOutBy'] != NULL) {
                echo "out";
            }
            else {
                //check the book is not requested
                $requests = $mysqli->query("SELECT id FROM request WHERE bid='".$_POST['deleteBook']."'");
                $reqNum = mysqli_num_rows($requests);

                if($reqNum != 0) {
                    echo "request";
                }
                else {
                    $mysqli->query("DELETE FROM Book WHERE id='".$_POST['deleteBook']."'");

                    echo true;
                }
            }
        }
        else if(isset($_POST['removeShelf'])) {
            $full = $mysqli->query("SELECT id FROM Book WHERE shelf='".$_POST['removeShelf']."'");
            $bookNum = mysqli_num_rows($full);

            if($bookNum != 0) {
                echo "full";
            }
            else {
                $mysqli->query("DELETE FROM Shelf WHERE id='".$_POST['removeShelf']."'");
                echo true;
            }
        }
    }

?>