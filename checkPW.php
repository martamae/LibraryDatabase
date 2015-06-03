<?php
    include "secret.php";

    session_start();

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

    //Get pin for user
    $password = $mysqli->query("SELECT pinNum FROM Person WHERE libraryCardNum='".$_SESSION['cardNum']."'");
    $pw = $password->fetch_assoc();

    //If the user wants to change pin #
    if (isset($_POST['pwReplace'])) {
        //Check uniqueness of new pin
        $unique = true;
        $pins = $mysqli->query("SELECT pinNum FROM Person");
        while ($row = $pins->fetch_assoc()){
            if ($row['pinNum'] == $_POST['new']){
                $unique = false;
                break;
            }
        }

        $length = strlen($_POST['new']);

       if ($length != 4) {
            echo "length";
       }
       else if(!is_numeric($_POST['new'])){
           echo "num";
       }
       else if(!$unique){
           echo "unique";
       }
       else if ($_POST['pw'] == $pw['pinNum']) {
           //Prepare statement
           if (!($statement = $mysqli->prepare("UPDATE libraryCard SET pinNum=? WHERE pinNum=?"))) {
                echo "new";
           }
           else {
               if(!($statement->bind_param('ii', $_POST['new'], $_POST['pw']))) {
                   echo "new";
               }
               else {
                   $statement->execute();
                   echo true;
               }
           }
        }
        else {
            //If not = to users pin echo false
            echo false;
        }
    }

    //If the user wants to delete their account
    if(isset($_POST['deleteCheck'])) {
        if ($_POST['pin'] == $pw['pinNum']) {
            echo true;
        }
        else {
            echo false;
        }
    }

    if(isset($_POST['delete'])) {
        //Get pin
        $pin = $_POST['pin'];

        //Delete the library card
        //Prepare statement
        $statement = $mysqli->prepare("DELETE FROM libraryCard WHERE pinNum = ?");

        $statement->bind_param('i', $pin);

        if ($statement->execute()) {
            //Delete the account
            //Prepare statement
            $smt = $mysqli->prepare("DELETE FROM Person WHERE pinNum = ?");

            $smt->bind_param('i', $pin);

            if ($smt->execute()) {
                //If the Person is deleted end session
                $_SESSION = array();
                session_destroy();
                header("Location: libraryHome.php", true);
                die();
            }
        }
    }
?>