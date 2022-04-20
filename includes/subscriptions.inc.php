<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["remove"])) {
        
        //remove sub row in db.
        $query = "DELETE FROM Subscriptions WHERE UserID = " . $_POST["subid"] . " AND SubscriptionID = " . $_SESSION["userId"] . ";";
        mysqli_query($link, $query);

	//refresh the page
        header("location: ../subscriptions.php");  
    }
        
?>
