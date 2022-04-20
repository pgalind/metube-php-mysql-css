<?php
// If the signin form was submitted, store the data passed in POST into local variables, and call loginUser
if (isset($_POST["submit"])) {
    $uid = $_POST["uid"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    loginUser($link, $uid, $pwd);
}
else {
    header("location: ../signin.php");
}