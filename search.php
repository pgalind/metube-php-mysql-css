<?php
    include_once 'header.php';
    
    //search keyword to parse files with
    $keyword = $_GET["key"];
    
    //retrieve array of all media files in the db
    $result = mysqli_query($link, 'SELECT * FROM Multimedia_Files ORDER BY Upload_Date DESC;');
    $media = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($media, $row); }
    mysqli_free_result($result);
    
    $haskey = array();
    foreach ($media as $curfile) {
    
        //converting to all lowercase will match more results
        $curkeys = strtolower($curfile["Keywords"]);
        $keyword = strtolower($keyword);
        
        //adds to results if the keyword is found in the current file's keywords
        if (strpos($curkeys, $keyword) !== false) { array_push($haskey, $curfile); }
    }
?>

<div align=center>
    <h1>Keyword Search results for '<?php echo $keyword; ?>'</h1>
</div>

<?php
    //print the search results
    foreach ($haskey as $curfile) {
        echo '<div class="metabox">
                  <h3 align=center><a href="./viewmedia.php?file=' . $curfile['Filename'] . '"> ' . $curfile['Title'] . '</a></h3>
              </div><br>';
    }
?>

<?php
    include_once 'footer.php';
?>
