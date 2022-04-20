<?php

//arbitrary session start use
session_start();
    
//include required functions
require_once 'functions.inc.php';
require_once 'dbh.inc.php';

//simply sets the ?cat=... GET var in the url
if (isset($_POST["browse"])){

    if ($_POST["category"] == 'img'){ header("location: ../browsemedia.php?cat=img"); }
    if ($_POST["category"] == 'vid'){ header("location: ../browsemedia.php?cat=vid"); }
    if ($_POST["category"] == 'aud'){ header("location: ../browsemedia.php?cat=aud"); }
    if ($_POST["category"] == 'rec'){ header("location: ../browsemedia.php?cat=rec"); }

}

?>
