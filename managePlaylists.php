<?php
    include_once 'header.php';

    //a user must be signed in to access playlist editting page.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view and manage playlists.
        </div>';
        exit();
    }

    //gather signed in user's playlists from db
    $userid = $_SESSION["userId"];
    $username = sqlSelect($link, 'userUid', 'Users', 'userId', $userid);
    $result = mysqli_query($link, 'SELECT * FROM Playlists WHERE userId = ' . $userid . ';');
    $playlists = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($playlists, $row); }
    mysqli_free_result($result);   
    
?>

    <h1 align=center> <?php echo $username; ?>'s Playlists </h1><br>
    <div class="metabox" id="optionsMetabox">
        <h3>Create New Playlist:</h3>
        <div class="playlistForm">
            <form action="includes/managePlaylists.inc.php" method="POST" enctype="multipart/form-data">
                <input type="text" name="Title" id="Title" placeholder="Title" required>
                <button type="submit" name="makenew" id="makenew"required><i class="fa fa-check" style="color:white;font-size:18px"></i></button>
            </form>
        </div>
    </div>

    <?php
    
        //loop through user's playlists and print options
        foreach ($playlists as $thislist){
            echo '
            <div class="metabox">
                <h3><a href="./viewPlaylist.php?playlistid=' . $thislist['PlaylistID'] . '"> ' . $thislist['Title'] . '</a></h3>
                <div class="playlistOpts">
                    <div class="playlistForm">
                        <form action="includes/managePlaylists.inc.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="PlaylistID" name="PlaylistID" value="' . $thislist["PlaylistID"] . '">
                            <input type="text" name="Title" id="Title" placeholder="Rename playlist" required>
                            <button type="submit" name="rename" id="rename"><i class="fa fa-pencil" style="color:white;font-size:18px"></i></button>
                        </form>
                    </div>
                    <div class="playlistForm">
                        <form action="includes/managePlaylists.inc.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="PlaylistID" name="PlaylistID" value="' . $thislist["PlaylistID"] . '">
                            <button type="submit" name="delete" id="delete"><i class="fa fa-trash" style="color:white;font-size:18px"></i></button>
                        </form>
                    </div>
                </div>                     
            </div><br>';
        }

    ?>
    
<?php
    include_once 'footer.php';
?>
