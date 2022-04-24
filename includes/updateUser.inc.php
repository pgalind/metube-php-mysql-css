<?php

// if the update profile form was submitted, store the data passed in POST into local variables
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $cur_pwd = $_POST["cur_pwd"];
    $pwd = $_POST["pwd"];
    $pwdconfirm = $_POST["pwdconfirm"];

    //arbitrary session start use
    session_start();

    // call functions to perform error handling
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    // get current user id
    $cur_id = $_SESSION["userId"]; // the only unique identifier that doesn't change; used to determine which user to update
    $cur_uid = sqlSelect($link, 'userUid', 'Users', 'userId', $cur_id); // current unchanged username


    if (invalidUid($uid) !== false) {
        header("location: ../updateUser.php?error=invalidusername");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../updateUser.php?error=invalidemail");
        exit();
    }
   
    if (wrongPwd($link, $cur_uid, $cur_pwd) !== false) {
        header("location: ../updateUser.php?error=wrongpassword");
        exit();
    }
    if (invalidPwd($pwd) !== false) {
        header("location: ../updateUser.php?error=invalidpassword");
        exit();
    }
    if (pwdMatch($pwd, $pwdconfirm) !== false) {
        header("location: ../updateUser.php?error=passwordsdontmatch");
        exit();
    }
    
    //this error check requires a query to check if other accounts have the new username or email that the user is requesting a change to
    $existsQuery = "SELECT * FROM Users WHERE (userUid = '" . $uid . "' OR userEmail = '" . $email . "') AND userId != '" . $cur_id . "';";
    if (mysqli_num_rows(mysqli_query($link, $existsQuery)) > 0) {
        header("location: ../updateUser.php?error=useralreadyexists");
        exit();
    }

    // if no errors are encountered, proceed to update user
    updateUser($link, $cur_id, $name, $uid, $email, $pwd);
    
}
else {
    header("location: ../updateUser.php");
}
