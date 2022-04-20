<?php
    include_once 'header.php';
    
    //file related variables gathered from ?file=... in the url.
    $file = $_GET['file'];
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    $path = "multimedia_uploads/" . $file;
    $type = getMediaType($file);
    $media = "";
    
    //the $media variable is html that will be constructed to display the type of the file.
    if ($type === 'IMG') {
        $media = '<img class="mediaborder" src="' . $path . '" alt="There was an issue loading.">';
    }
    
    else if ($type === 'VID') {
        $media = '<video class="mediaborder" width="60%" height="auto" controls>
                      <source src="' . $path . '" type="video/' . $ext . '">
                      Video is not supported by the browser.
                  </video>';
    }
    
    else if ($type = 'AUD') {
        $media = '<audio controls>
                      <source src="' . $path . '" type="audio/' . $ext . '">
                      Audio is not supported by the browser.
                  </audio>';
    }
    
    //retrieving all the variables needed about the media file for the interface's content.
    $title = sqlSelect ($link, 'Title', 'Multimedia_Files', 'Filename', $file);
    $desc = sqlSelect ($link, 'Description', 'Multimedia_Files', 'Filename', $file);
    $keywords = sqlSelect ($link, 'Keywords', 'Multimedia_Files', 'Filename', $file);
    $date = sqlSelect ($link, 'Upload_Date', 'Multimedia_Files', 'Filename', $file);
    $uid = sqlSelect ($link, 'UserId', 'Multimedia_Files', 'Filename', $file);
    $creatorusername = sqlSelect ($link, 'userUid', 'Users', 'userId', $uid);
    $fileid = sqlSelect ($link, 'FileId', 'Multimedia_Files', 'Filename', $file);
    
?>
    <div class="mediaContainer">
    <h1 align=center> <?php echo $title ?> </h1>
    
    <div style="text-align: center;">
        <?php echo $media ?>
    </div>
    
    <div class="metabox">
        <h3>Uploaded by <a href="<?php echo './userChannel.php?userid=' . $uid . '"> ' . $creatorusername . '</a> on ' . $date; ?></h3><br>
        <h3>Description</h3>
        <p><?php echo $desc; ?> </p><br>
        <h3>Keywords</h3>
        <p><?php echo $keywords; ?></p>
    </div>
    
    <?php
        //playlist and favorites options only show up if logged in
        if (isset($_SESSION["userId"])) {
        
            //this checks if the current logged in user is subscribed to this channel
            $favcheck = mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Favorites WHERE UserId = ' . $_SESSION["userId"] . ' AND FileId = ' . $fileid . ';'));        
            echo '
            <div class="mediaOpts">
                <div class="OptAndButton">';
            //favorite button displayed if file isn't favorited 
            if ($favcheck == 0) {
                echo '
                <form action="includes/viewmedia.inc.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="fileid" name="fileid" value="' . $fileid . '" >
                    <input type="hidden" id="filename" name="filename" value="' . $file . '">
                    <label for="fav">Add to Favorites</label>                     
                    <button type="submit" name="fav" id="fav"><i class="fa fa-heart-o" style="color:black;font-size:24px;padding:5px 1px"></i></button>
                </form>';
            }
            
            //unfavorite button displayed if file is favorited 
            if ($favcheck == 1) {
                echo '
                <form action="includes/viewmedia.inc.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" id="fileid" name="fileid" value="' . $fileid . '" >
                    <input type="hidden" id="filename" name="filename" value="' . $file . '">
                    <label for="unfav">Remove from Favorites</label>
                    <button type="submit" name="unfav" id="unfav"><i class="fa fa-heart" style="color:red;font-size:24px"></i></button>
                </form>';
            }                         
            echo '
                </div>';
            
            $result = mysqli_query($link, 'SELECT * FROM Playlists WHERE userId = ' . $_SESSION["userId"] . ';');
            $playlists = array();
            while ($row = mysqli_fetch_assoc($result)) { array_push($playlists, $row); }
            mysqli_free_result($result);
            
            //only display 'add to playlist' form if the user has created playlists       
            if (count($playlists) > 0) {    
                echo '
                <div class="OptAndButton">
                    <form action="includes/viewmedia.inc.php" method="POST" enctype="multipart/form-data">
                        <label for="playlists">Add to Playlist</label>
                        <select name="playlists" id="playlists">';
                        foreach ($playlists as $thislist) {
                            echo '<option value="' . $thislist["PlaylistID"] . '">' . $thislist["Title"] . '</option>';
                        }
                    echo '
                        </select>
                        <input type="hidden" id="file" name="file" value="' . $file . '">
                        <input type="hidden" id="fileid" name="fileid" value="' . $fileid . '">
                        <button type="submit" name="playlistadd"><i class="fa fa-check-square-o" style="color:black;font-size:24px"></i></button>           
                    </form>
                </div>';
            }

            echo '
                <div class="OptAndButton">
                <label for="download">Download File</label>
                <button type=button> 
                    <a href="' . $path . '" download="' . $file . '">
                    <i class="fa fa-download" style="color:black;font-size:24px"></i></a>
                </button> 
                </div>
            </div>
            ';
        }
    ?>
    
    <p align=center><?php if (isset($_SESSION["fail"])) { echo $_SESSION["fail"]; }?></p>
     
    <h1 align=center> Comments </h1>
    
    <?php
    
    function printComment($link, $comment) {

        $commenterName = sqlSelect ($link, 'userUid', 'Users', 'userId', $comment["SenderID"]);
            
            echo 
            '<div class="comment">
                ' . $commenterName . ' commented on ' . $comment["DatePosted"] . '<br>'
                . $comment["CommentText"] . '<br>';
            
            //reply form only shows up if user is logged in
            if (isset($_SESSION["userId"]) === TRUE) {
                echo 
                    '<div id="' . $comment["CommentID"] . '" style="display: none;"> 
                    <form action="includes/viewmedia.inc.php" method="POST" enctype="multipart/form-data">
                        <p> Your Reply: </p>
                        <textarea name="comment" id="replyArea" placeholder="Type your new comment here. Max 255 characters." required></textarea>
                        <input type="hidden" id="filename" name="filename" value="' . $_GET['file'] . '">
                        <input type="hidden" id ="reply" name="reply" value="' . $comment["CommentID"] . '">
                    <br><input type="submit" value="Post Comment" name="submit" required>
                    </form></div>';
                echo '<button type="button" style="width: 100px;" id="b' . $comment["CommentID"] . '" onclick="showReply(' . $comment["CommentID"] . ')"> Reply </button>';
            }
            
            //finishing comment class div from earlier.
            echo '</div><br>';
            
            //gather all direct replies to the given comment.
            $result = mysqli_query($link, "SELECT ReplyID FROM Replies WHERE CommentID = " . $comment["CommentID"] . ";");
        
            $matches = array();
            while ($row = mysqli_fetch_assoc($result)) { array_push($matches, $row); }
            mysqli_free_result($result);
            
            foreach($matches as $row) {
                $result = mysqli_query($link, "SELECT * FROM Comments WHERE CommentID = " . $row["ReplyID"] . ";");
                $reply = mysqli_fetch_assoc($result);
                mysqli_free_result($result);
                echo '<div style="padding-left: 10%; text-align: left;">Reply to ' . $commenterName . ':<br>';
                printComment($link, $reply);
                echo '</div>';
            }
        
        }
        
        //comment box only shows up if user is logged in
        if (isset($_SESSION["userId"]) === TRUE) {
            echo 
            '<form action="includes/viewmedia.inc.php" method="POST" enctype="multipart/form-data" align=center>
                <textarea name="comment" id="commentArea" placeholder="Type your new comment here. Max 255 characters." style="width: 80%;" required></textarea>
                <input type="hidden" id="filename" name="filename" value="' . $file . '">
                <br><button type="submit" name="submit" required>Post Comment</button>
            </form><br>';
        }
        
        //all current comments are pulled from the db
        $result = mysqli_query ($link, "SELECT * FROM Comments WHERE FileID = " . $fileid . " ORDER BY DatePosted ASC;");
        
        //this section stores the comments in an array variable. This allows the result set to be 
        //closed. Now comments can be looped through while performing additional sql queries.
        $comments = array();
        while ($row = mysqli_fetch_assoc($result)) { array_push($comments, $row); }
        mysqli_free_result($result);
        
        echo '<div class="commentsection">';
        
        //comments are printed
        foreach($comments as $row) {
            
            //comment will only be printed if it is found not to be a reply to anything
            $replyCheck = mysqli_query($link, "SELECT * FROM Replies WHERE ReplyID = " . $row["CommentID"] . ";");
            if (mysqli_num_rows($replyCheck) != 0) { mysqli_free_result($replyCheck); }
            else {
                mysqli_free_result($replyCheck);
                printComment($link, $row);
            }
        }
        
        echo '</div>';
    ?>
    <script>
        function showReply(id) { 
        document.getElementById(id).style.display = "block";
        }
    </script>
    
    <br>
    </div>
		
<?php
    include_once 'footer.php';
?>
