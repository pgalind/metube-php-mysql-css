<?php
    include_once 'header.php';
    
    //a user must be signed in to access their favorites page.
    if (isset($_SESSION["userId"]) === FALSE) {
	echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view your Favorites.
        </div>';
	exit();
	}

    $username = sqlSelect($link, 'userUid', 'Users', 'userId', $_SESSION["userId"]);

?>

    <h1 align=center><?php echo $username; ?>'s Favorites</h1><br>
        

<?php

    //get media files with a nested sql query
    $result = mysqli_query($link, 'SELECT * FROM Multimedia_Files WHERE FileId IN (SELECT FileId FROM Favorites WHERE UserId = ' . $_SESSION["userId"] . ');');
    $media = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($media, $row); }
    mysqli_free_result($result);
    
    //display message if playlist is empty
    if (count($media) == 0) { echo "<h3 align=center>You don't have any favorites.</h3>"; }
            
    //loop through all the favorites and print view links
    foreach($media as $row) {
        echo '<div class="metabox">
                  <h3><a href="./viewmedia.php?file=' . $row['Filename'] . '"> ' . $row['Title'] . '</a></h3>
                  <form action="includes/favorites.inc.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" id="fileid" name="fileid" value="' . $row["FileId"] . '">
                      <button type="submit" name="remove">Remove file from Favorites</button>
                  </form>
              </div><br>';
        }
?>

<?php
    include_once 'footer.php';
?>
