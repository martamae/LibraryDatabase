<?php
    include "secret.php";

    //Connecting to database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }


    if (isset($_POST['join'])) {
        //Check uniqueness of new pin
        $unique = true;
        $pins = $mysqli->query("SELECT pinNum FROM Person");
        while ($row = $pins->fetch_assoc()){
            if ($row['pinNum'] == $_POST['pinNum']){
                $unique = false;
                break;
            }
        }

        //Check the length of pin
        $length = strlen($_POST['pinNum']);

        if ($length != 4) {
            echo "length";
        }
        else if(!is_numeric($_POST['pinNum'])){
            echo "num"; //Check that pin is numeric
        }
        else if(!$unique){
             echo "unique";
        }
        else {
            //Create Library Card
            //Prepare statement
            if (!($smt = $mysqli->prepare("INSERT INTO libraryCard(pinNum, dateIssued) VALUES (?, curdate())"))) {
                echo false;
            }
            else {
                if(!($smt->bind_param('i', $_POST['pinNum']))) {
                    echo false;
                }
                else {
                    if(!($smt->execute())) {
                        echo false;
                    }
                    else {
                        //Create Person
                        if (!($statement = $mysqli->prepare("INSERT INTO Person(DOB, fname, lname, pinNum, libraryCardNum)
                                                VALUES (?, ?, ?, ?, (SELECT id FROM libraryCard WHERE pinNum= ?))"))){
                            echo false;
                        }
                         else {
                             if(!($statement->bind_param('sssii', $_POST['DOB'], $_POST['fname'], $_POST['lname'], $_POST['pinNum'], $_POST['pinNum']))){
                                echo false;
                             }
                             else {
                                 if (!($statement->execute())) {
                                     echo false;
                                 }
                                 else {
                                     //select card number to start session and print to user
                                     $num = $mysqli->query("SELECT id FROM libraryCard WHERE pinNum='".$_POST['pinNum']."'");
                                     $cardNum = $num->fetch_assoc();

                                     //Start session
                                     session_start();

                                     if(session_status() == PHP_SESSION_ACTIVE) {
                                         //set session variable to log patron in
                                         $_SESSION['cardNum'] = $cardNum['id'];
                                     }

                                     echo $cardNum['id'];
                                 }
                             }
                         }
                    }
                }
             }
        }
    }

    $mysqli->close();
?>
