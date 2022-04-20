<?php
    include_once 'header.php';
  
    //playlist id is gathered from ?playlistid=... in the url. Other variables obtained as necessary.
    $playlistid = $_GET['playlistid'];
    $playlistname = sqlSelect($link, 'Title', 'Playlists', 'PlaylistID', $playlistid);
    $creatorid = sqlSelect($link, 'userId', 'Playlists', 'PlaylistID', $playlistid);
    
    //get media files with a nested sql query
    $result = mysqli_query($link, 'SELECT * FROM Multimedia_Files WHERE FileId IN (SELECT FileId FROM Playlist_Files WHERE PlaylistID = ' . $playlistid . ');');
    $media = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($media, $row); }
    mysqli_free_result($result);
    
?>

    <div align=center>
        <br><h1> Files in Playlist: <?php echo $playlistname; ?> </h1><br>
        <?php
        
            //display message if playlist is empty
            if (count($media) == 0) { echo '<h3>Playlist is currently empty.</h3>'; }
        
            //loop through all the files and print view links
            foreach($media as $row) {
                echo '<div class="metabox">
                          <h3><a href="./viewmedia.php?file=' . $row['Filename'] . '"> ' . $row['Title'] . '</a></h3>';
                
                //user can only remove from playlist if they are logged in as the playlist creator
                if (isset($_SESSION["userId"]) && ($_SESSION["userId"] == $creatorid)) {
                    echo
                        '<form action="includes/viewPlaylist.inc.php" method="POST" enctype="multipart/form-data">
                             <input type="hidden" id="fileid" name="fileid" value="' . $row["FileId"] . '">
                             <input type="hidden" id="playlistid" name="playlistid" value="' . $playlistid . '">
                             <input type="submit" style="background-color:red;" value="Remove File from Playlist" name="remove">
                         </form>';
                }
            
                echo '</div><br>';
            }
        ?>        
    </div>

<?php
    include_once 'footer.php';
?>
