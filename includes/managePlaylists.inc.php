<?php

    //arbitrary session start use
    session_start();
    
    //include required functions
    require_once 'functions.inc.php';
    require_once 'dbh.inc.php';
    
    if (isset($_POST["makenew"])) {
        
        //finding current date and time
        $date = new DateTime();
        $creationdate = date_format($date, 'Y-m-d-H-i-s');        
        
        //insert new playlist into db
        $query = "INSERT INTO Playlists (userId, Title, CreationDate) VALUES ('" . $_SESSION["userId"] . "', '" . $_POST["Title"] . "', '" . $creationdate . "');";
        mysqli_query($link, $query);
    }
    
    if (isset($_POST["delete"])) {
        
        //delete the selected playlist from the db
        $query = "DELETE FROM Playlists WHERE PlaylistID = '" . $_POST["PlaylistID"] . "';";
        mysqli_query($link, $query);
        
        //delete from Playlist_Files
        $query = "DELETE FROM Playlist_Files WHERE PlaylistID = '" . $_POST["PlaylistID"] . "';";
        mysqli_query($link, $query);
    }
    
    if (isset($_POST["rename"])) {
        
        //update the name of the db entry
        $query = "UPDATE Playlists SET Title = '" . $_POST["Title"] . "' WHERE PlaylistID = '" . $_POST["PlaylistID"] . "';";
        mysqli_query($link, $query);
    }
    
    //refresh the page
    header("location: ../managePlaylists.php?userid=" . $_SESSION["userId"]);
    
?>
