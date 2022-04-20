<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["remove"])) {
        
        //remove file from user's favorites in db
        $query = "DELETE FROM Favorites WHERE UserId = " . $_SESSION["userId"] . " AND FileId = " . $_POST["fileid"] . ";";
        mysqli_query($link, $query);

	//refresh the page
        header("location: ../favorites.php");  
    }
    
?>
