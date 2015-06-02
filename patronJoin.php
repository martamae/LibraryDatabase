<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script src = 'libScript.js'></script>
    <link rel="Stylesheet" href="libStyle.css">
    <title>Join Library</title>
</head>
<body class="home">
<div class="header"><h1>JOIN THE LIBRARY</h1></div>
<div class="joinLib">
    <div class="joinText">
        <div class="joinInput"><p class="message" id="jMessage"></p></div>
        <form class="joinInput" method="POST">
            <p>First name:</p>
            <input type="text" name="fname" id="fname">
            <p>Last name:</p>
            <input type="text" name="lname" id="lname">
            <p>Pin number:</p>
            <input type="password" name="pinNum" id="pinNum">
            <p>Birthday</p>
            <?php
                //Calculate date
                $thirteenYrs = mktime(0, 0, 0, date("m"), date("d"), date("Y")-13);
                $date = date("Y-m-d", $thirteenYrs);

                echo '<input type="date" name="DOB" min="'.$date.'" required>';
            ?>
            <br>
            <input type="button" class="jButton" name="join" value="Join" onclick="validateJoin()">
        </form>
    </div>
</div>

</body>
</html>
