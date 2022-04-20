<?php

//arbitrary session start use
session_start();
    
//include required functions
require_once 'functions.inc.php';
require_once 'dbh.inc.php';

if (isset($_POST["submit"])) {
    header("location: ../search.php?key=" . $_POST["search"]);
}

?>
