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
            $cardList = $mysqli->query("SELECT libraryCardNum FROM Person");
            $valid = false;

            while ($row = $cardList->fetch_assoc() AND $valid == false){
                if ($row['libraryCardNum'] == $libNum) {
                    $valid = true;
                }
            }
        }

        if ($valid == true) {
            //Check pinNum is in table
            $pinList = $mysqli->query("SELECT pinNum FROM Person");
            $valid = false;
            while ($row = $pinList->fetch_assoc() AND $valid == false) {
                if ($row['pinNum'] == $pinNum) {
                    $valid = true;
                }
            }
        }

        echo $valid;
    }
?>