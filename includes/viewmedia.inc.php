<?php

//arbitrary session start use
session_start();
    
//include required functions
require_once 'functions.inc.php';
require_once 'dbh.inc.php';

if (isset($_POST["submit"])) {
    
    //variables needed for database insertion function
    $uid = $_SESSION["userId"];
    $comment = $_POST["comment"];
    $filename = $_POST["filename"];
    $fileID = sqlSelect ($link, 'FileId', 'Multimedia_Files', 'Filename', $filename);
    
    insertComment($link, $filename, $fileID, $uid, $comment);
    
    if (isset($_POST["reply"])) {
        $result = mysqli_query($link, "SELECT MAX(CommentID) FROM Comments;");
        $replyID = (mysqli_fetch_row($result))[0];
        mysqli_free_result($result);
        mysqli_query($link, "INSERT INTO Replies (CommentID, ReplyID) VALUES (" . $_POST["reply"] . ", " . $replyID . ");");
    }
            
    //page is refreshed to view the new posted comment
    header("location: ../viewmedia.php?file=" . $filename);
}

if (isset($_POST["playlistadd"])) {

    //resetting the fail message variable
    unset($_SESSION["fail"]);
        
    //error case: selected playlist already contains the file
    if (mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Playlist_Files WHERE FileId = ' . $_POST["fileid"] . ' AND PlaylistID = ' . $_POST["playlists"] . ';')) > 0) {
        $_SESSION["fail"] = "This file is already in the selected playlist.";
    }

    else {
        //add the file to the selected playlist
        $query = 'INSERT INTO Playlist_Files (FileId, PlaylistID) VALUES (' . $_POST["fileid"] . ', ' . $_POST["playlists"] . ');';
        mysqli_query($link, $query);
    }
    
    //page is refreshed
    header("location: ../viewmedia.php?file=" . $_POST["file"]);
}

if (isset($_POST["fav"])) {

    //add this file to user's favorites in db
    mysqli_query($link, 'INSERT INTO Favorites (UserId, FileId) VALUES (' . $_SESSION["userId"] . ', ' . $_POST["fileid"] . ');');
    
    //page is refreshed
    header("location: ../viewmedia.php?file=" . $_POST["filename"]);    
}

if (isset($_POST["unfav"])) {

    //remove file from User's favorites in db
    mysqli_query($link, 'DELETE FROM Favorites WHERE UserId = ' . $_SESSION["userId"] . ' AND FileId = ' . $_POST["fileid"] . ';');
    
    //page is refreshed
    header("location: ../viewmedia.php?file=" . $_POST["filename"]);    
}
    
else {
    echo "<p>Something went wrong. Please refresh the page and try again.</p>";
}

?>
