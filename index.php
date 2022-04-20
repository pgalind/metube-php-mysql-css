<?php
    include_once 'header.php';
    
    //index redirects to the 'most recent' browse category.
    //this makes it so that most recent uploads serve as a sort
    //of home page for MeTube.
    header("location: ./browsemedia.php?cat=rec");
?>

<?php
    include_once 'footer.php';
?>
