<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["submit"])) {
    
        //finding current date and time
        $date = new DateTime();
        $sendDate = date_format($date, 'Y-m-d-H-i-s');
        
        //inserting new message
	mysqli_query($link, "INSERT INTO Messages (SenderID, ReceiverID, Message, ReplyMessage, DateSent) VALUES (" . $_SESSION["userId"] . 
	", " . $_POST["uid"] . ", '" . $_POST["message"] . "', '" . $_POST["reply"] . "', '" . $sendDate . "')");
	
        //page is refreshed
        header("location: ../chat.php?uid=" . $_POST["uid"]);    
    }
    
?>
