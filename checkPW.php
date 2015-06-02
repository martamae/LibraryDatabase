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

    if (isset($_POST['pwReplace'])) {
        $length = strlen($_POST['new']);

       if ($length > 6 OR $length < 4) {
            echo "length";
       }
        else if ($_POST['pw'] == $pw['pinNum']) {
            echo true;
        }
        else {
            //If not = to users pin echo false
            echo false;
        }
    }

//card
$mysqli->query("DELETE FROM libraryCard WHERE libraryCardNum='".$_SESSION['cardNum']."'");

//Create new card
$mysqli->query("INSERT INTO libraryCard");
?>