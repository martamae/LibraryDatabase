<?php
    include "secret.php";

    //Connecting to database
    $mysqli = new mysqli("oniddb.cws.oregonstate.edu", "wegnerma-db", $password, "wegnerma-db");

    if (!$mysqli|| $mysqli->connect_errno) {
        echo "Failed to connect:" . $mysqli->connect_errno . " " . $mysqli->connect_error;
    }

    //Check uniqueness of new pin
    $unique = true;
    $pins = $mysqli->query("SELECT pinNum FROM Person");
    while ($row = $pins->fetch_assoc()){
        if ($row['pinNum'] == $_POST['pinNum']){
            $unique = false;
            break;
        }
    }

    if (isset($_POST['join'])) {
        $length = strlen($_POST['pinNum']);

        if ($length > 6 OR $length < 4) {
            echo "length";
        }
    else if(!is_numeric($_POST['pinNum'])){
        echo "num";
    }
    else if(!$unique){
        echo "unique";
    }
//    else {
//        //Prepare statement
//        if (!($statement = $mysqli->prepare("UPDATE libraryCard SET pinNum=? WHERE pinNum=?"))) {
//            echo "new";
//        }
//        else {
//            if(!($statement->bind_param('ii', $_POST['new'], $_POST['pw']))) {
//                echo "new";
//            }
//            else {
//                $statement->execute();
//                echo true;
//            }
//        }
//    }
}

//card
$mysqli->query("DELETE FROM libraryCard WHERE libraryCardNum='".$_SESSION['cardNum']."'");

//Create new card
$mysqli->query("INSERT INTO libraryCard");
?>
