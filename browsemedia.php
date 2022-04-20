<?php
    include_once 'header.php';
    
    //retrieve array of all media files in the db
    $result = mysqli_query($link, 'SELECT * FROM Multimedia_Files ORDER BY Upload_Date DESC;');
    $media = array();
    $cat = $_GET["cat"];
    while ($row = mysqli_fetch_assoc($result)) { array_push($media, $row); }
    mysqli_free_result($result);
    
    //header printed based off of selection.
    echo '<h3 align=center>Current Browse Category:</h3>';
    if ($cat == 'img') { echo '<h1 align=center>Image/GIF Files</h1>'; }
    if ($cat == 'vid') { echo '<h1 align=center>Video Files</h1>'; }
    if ($cat == 'aud') { echo '<h1 align=center>Audio Files</h1>'; }
    if ($cat == 'rec') { echo '<h1 align=center>Most Recently Uploaded Files</h1>'; }
    
?>

    <div align=center><br>
        <form action="includes/browsemedia.inc.php" method="POST" enctype="multipart/form-data">
            <select name="category" id="category">
                <option value="img">Images/Gifs</option>
                <option value="vid">Videos</option>
                <option value="aud">Audio</option>
                <option value="rec">Most Recently Uploaded</option>
            </select>
            <input type="submit" value="Browse" name="browse">
        </form>
    </div><br>
    
<?php

    //will hold media to be displayed based on user's category selection.
    $browseresults = array();

    //these statements sort/ filter the browse results based on selection.
    if ($_GET["cat"] == 'img'){
        foreach ($media as $curfile) {
            if (getMediaType($curfile["Filename"]) == 'IMG'){ array_push($browseresults, $curfile); }
        }
    }
    
    if ($_GET["cat"] == 'vid'){
        foreach ($media as $curfile) {
            if (getMediaType($curfile["Filename"]) == 'VID'){ array_push($browseresults, $curfile); }
        }        
    }
    
    if ($_GET["cat"] == 'aud'){
        foreach ($media as $curfile) {
            if (getMediaType($curfile["Filename"]) == 'AUD'){ array_push($browseresults, $curfile); }
        }        
    }
    
    //media is already sorted by recent, so only simple assignment necessary.
    //Recent serves as a sort of default search category.
    if ($_GET["cat"] == 'rec'){ $browseresults = $media; }
    
    //print the browse results
    foreach ($browseresults as $curfile) {
        echo '<div class="metabox">
                  <h3 align=center><a href="./viewmedia.php?file=' . $curfile['Filename'] . '"> ' . $curfile['Title'] . '</a></h3>
              </div><br>';
    }

?>    

<?php
    include_once 'footer.php';
?>
