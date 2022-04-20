<?php

if (isset($_POST["submit"])) {

    //arbitrary session start use
    session_start();

    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    //necessary filepath and naming variables
    $upload_dir = "../multimedia_uploads/";
    $filename = basename($_FILES["uploadSelection"]["name"]);
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);

    //this section ensures that filenames are not duplicated. This is done by adding a number to the name
    //(before the file extension) until it is unique within the uploads folder.
    for ($i = 0; file_exists($upload_dir . $filename) === true; $i++) {
        $filename = basename($_FILES["uploadSelection"]["name"], "." . $file_ext) . $i . "." . $file_ext;
    	}
    
    //file is moved from the temporary space to the webapp folder
    move_uploaded_file($_FILES["uploadSelection"]["tmp_name"], $upload_dir . $filename);
    
    //variables needed for database insertion function
    $uid = $_SESSION["userId"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $keywords = $_POST["keywords"];
    
    //inserting file into database
    insertMedia ($link, $uid, $title, $description, $keywords, $filename);
}

else {
    echo "<p>Something went wrong. Please refresh the page and try again.</p>";
}

?>
