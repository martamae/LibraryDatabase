<?php
    include "secret.php";

    //Connecting
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }


    //Patron logon
    if (isset($_POST["patronLog"])) {
        $libNum = $_POST["cardNum"];
        $pinNum = $_POST["pin"];

        //Validate input
        //NULL input = invalid
        $valid = true;
        if ($libNum == NULL || $pinNum == NULL) {
            $valid = false;
        }

        if ($valid == true) {
            //Check libraryCardNum is in table
            $patronList = $mysqli->query("SELECT libraryCardNum, pinNum FROM Person");
            $valid = false;

            while ($row = $patronList->fetch_assoc() AND $valid == false){
                if ($row['libraryCardNum'] == $libNum) {
                    if ($row['pinNum'] == $pinNum) {
                        $valid = true;
                    }
                }
            }
        }

        echo $valid;
    }

    //Librarian logon
    if (isset($_POST["librarianLog"])) {
        $username = $_POST["username"];
        $pinNum = $_POST["pin"];

         //Validate input
         //NULL input = invalid
        $valid = true;
        if ($username == NULL || $pinNum == NULL) {
            $valid = false;
        }

        if ($valid == true) {
             //Check username is in table
            $librarianList = $mysqli->query("SELECT username, pinNum FROM Librarian");
             $valid = false;

            while ($row = $librarianList->fetch_assoc() AND $valid == false){
                if ($row['username'] == $username) {
                    if ($row['pinNum'] == $pinNum) {
                        $valid = true;
                    }
                }
            }
        }

        echo $valid;
    }
?>