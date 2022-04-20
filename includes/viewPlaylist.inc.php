<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["remove"])) {
        
        //remove file from the playlist
        $query = "DELETE FROM Playlist_Files WHERE FileId = " . $_POST["fileid"] . ";";
        mysqli_query($link, $query);

	//refresh the page
        header("location: ../viewPlaylist.php?playlistid=" . $_POST["playlistid"]);  
    }
    
?>
