<?php
    include_once 'header.php';

    //form can only be accessed for logged in users.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to upload files.
        </div>';
        exit();
    }
?>

<div class="box">
    <h2>Upload File</h2>
    <form action="includes/upload.inc.php" method="POST" enctype="multipart/form-data">
        <div class="inputBox">
            <input type="file" name="uploadSelection" id="uploadSelection" required>
        </div>
        <div class="inputBox">
            <input type="text" name="title" id="title" placeholder="upload title" required>
        </div>
        <div class="inputBox">
            <input type="text" name="description" id="description" placeholder="description of file" required>
        </div>
        <div class="inputBox">
            <input type="text" name="keywords" id="keywords" placeholder="relevant keywords" required>
        </div>
        <button type="submit" name="submit" required><i class="fa fa-upload" style="color:black;font-size:16px;padding:3px 6px"></i>Upload</button>
    </form>
</div>


<?php
    include_once 'footer.php';
?>
