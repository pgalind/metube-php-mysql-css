<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["submit"])) {
    
            $sendid = $_POST["sendto"];
            $usercheck = sqlSelect ($link, 'userUid', 'Users', 'userId', $sendid);
        
	    //resetting session 'fail' variable before every error check
	    unset($_SESSION["fail"]);
	    
	    //error case for if entered id is not found in db
	    if ($usercheck == false) {
		$_SESSION["fail"] = "This ID was not found. Please try again.";
	    }
	    
	    //error case for if user enters their own id
	    else if ($_SESSION["userId"] == $sendid) {
		$_SESSION["fail"] = "You cannot send a message to yourself.";
	    }
	    
	    //error case for if user enters an ID that they already have messages with
	    else if (mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Messages WHERE (SenderID = ' 
	    . $_SESSION["userId"] . ' AND ReceiverID = ' . $sendid . ') OR (SenderID = ' . $sendid . ' AND ReceiverID = ' . $_SESSION["userId"] . ');')) > 0){
		$_SESSION["fail"] = "You already have a message log with this user.";
	    }
	    
	    //correct case; db insertion performed.
	    else {
	        //finding current date and time
                $date = new DateTime();
                $sendDate = date_format($date, 'Y-m-d-H-i-s');
                
                //inserting new message
	        mysqli_query($link, "INSERT INTO Messages (SenderID, ReceiverID, Message, ReplyMessage, DateSent) VALUES (" . $_SESSION["userId"] . 
	        ", " . $sendid . ", '" . $_POST["message"] . "', '', '" . $sendDate . "');");
	    }
	    
	    //page is refreshed
            header("location: ../messages.php");
    }
    
    else {
        echo "<p>Something went wrong. Please refresh the page and try again.</p>";
    }
    
?>
