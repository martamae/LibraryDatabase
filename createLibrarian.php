<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src = 'libScript.js'></script>
    <link rel="Stylesheet" href="libStyle.css">
    <title>Create Librarian Account</title>
</head>
<body class="home">
<div class="header"><h1>CREATE AN ACCOUNT</h1></div>
<div class="joinLib">
    <div class="joinText">
        <div class="joinInput"><p class="message" id="createMessage"></p></div>
        <form class="joinInput" method="POST">
            <p>First name:</p>
            <input type="text" name="fname" id="fname">
            <p>Last name:</p>
            <input type="text" name="lname" id="lname">
            <p>Username:</p>
            <input type="text" name="username" id="username">
            <p>Four digit pin number:</p>
            <input type="password" name="pinNum" id="pinNum">
            <p>Birthday</p>
            <?php
            //Calculate date
            $eighteenYrs = mktime(0, 0, 0, date("m"), date("d"), date("Y")-18);
            $date = date("Y-m-d", $eighteenYrs);

            echo '<input type="date" name="DOB" id="DOB" min="'.$date.'" required>';
            ?>
            <br>
            <input type="button" class="jButton" name="join" value="Join" onclick="validateCreate()">
        </form>
    </div>
</div>

</body>
</html>

<?php
$mysqli->close();
?>