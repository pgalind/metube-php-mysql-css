<?php

if (isset($_POST["submit"])) {

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    //if the form was used to delete a contact, it does that then stops execution.
    if (isset($_POST["delete"])){
        mysqli_query($link, 'DELETE FROM Contacts WHERE UserID = ' . $_SESSION["userId"] . ' AND ContactID = ' . $_POST["delete"] . ';');
        
        //page is refreshed to view the newly deleted contact
        header("location: ../contacts.php");
        exit();
    }
    
    //variables needed for database insertion function
    $uid = $_SESSION["userId"];
    $contactid = $_POST["contactid"];
    $contactcheck = sqlSelect ($link, 'userUid', 'Users', 'userId', $contactid);
    
    //resetting session 'fail' variable before every error check
    unset($_SESSION["fail"]);
    
    //error case for if entered id is not found in db
    if ($contactcheck == false) {
        $_SESSION["fail"] = "This ID was not found. Please try again.";
    }
    
    //error case for if user enters their own id
    else if ($uid == $contactid) {
        $_SESSION["fail"] = "You cannot add yourself as a contact.";
    }
    
    //error case for if user enters a contact already in the db
    else if (mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Contacts WHERE UserID = ' . $uid . ' AND ContactID = ' . $contactid . ';')) > 0){
        $_SESSION["fail"] = "This contact has already been added.";
    }
    
    //correct case; db insertion performed
    else {
        mysqli_query($link, 'INSERT INTO Contacts (UserID, ContactID) VALUES (' . $uid . ', ' . $contactid . ');');
    }
        
    //page is refreshed to view the newly added contact
    header("location: ../contacts.php");
}
    
else {
    echo "<p>Something went wrong. Please refresh the page and try again.</p>";
}

?>
