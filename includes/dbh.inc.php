<?php

/* Database variables */
$DB_SERVER = "mysql1.cs.clemson.edu";
$DB_USERNAME = "pgalind";
$DB_PASSWORD = "cpsc4620S22";
$DB_NAME = "MeTubeS22";
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect($DB_SERVER, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
 
// Check connection
if($link === false){
    die("Failed to connect: " . mysqli_connect_error());
}