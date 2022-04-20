<?php

// if the signup form was submitted, store the data passed in POST into local variables
if (isset($_POST["submit"])) {
    $name = $_POST["name"];
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdconfirm = $_POST["pwdconfirm"];

    // call functions to perform error handling
    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if (invalidUid($uid) !== false) {
        header("location: ../signup.php?error=invalidusername");
        exit();
    }
    if (invalidEmail($email) !== false) {
        header("location: ../signup.php?error=invalidemail");
        exit();
    }
    if (invalidPwd($pwd) !== false) {
        header("location: ../signup.php?error=invalidpassword");
        exit();
    }
    if (pwdMatch($pwd, $pwdconfirm) !== false) {
        header("location: ../signup.php?error=passwordsdontmatch");
        exit();
    }
    if (uidExists($link, $uid, $email) !== false) {
        header("location: ../signup.php?error=useralreadyexists");
        exit();
    }

    // if no errors are encountered, proceed to create user
    createUser($link, $name, $uid, $email, $pwd);
}
else {
    header("location: ../signup.php");
}