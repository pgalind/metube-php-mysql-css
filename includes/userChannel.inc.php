<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    //insert new subscription into db if subscribe button was clicked
    if (isset($_POST["sub"])) {
        mysqli_query($link, 'INSERT INTO Subscriptions (UserID, SubscriptionID) VALUES (' . $_POST["userid"] . ', ' . $_SESSION["userId"] . ');');
    }
    
    //remove subsription from db if unsubscribe was clicked
    if (isset($_POST["unsub"])) {
        mysqli_query($link, 'DELETE FROM Subscriptions WHERE UserID = ' . $_POST["userid"] . ' AND SubscriptionID = ' . $_SESSION["userId"] . ';');
    }
    
    header("location: ../userChannel.php?userid=" . $_POST["userid"]);

?>
