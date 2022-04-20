<?php
    session_start();
    include_once 'includes/dbh.inc.php';
    include_once 'includes/functions.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MeTube</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <div class="options-bar">

    <div class="dropdown-options">
        <button class="drop-btn"><i class="fa fa-navicon" style="color:black;font-size:24px;padding:5px"></i></button>
        <div class="drop-menu options">
            <a href="./"><i class="fa fa-home" style="color:black;font-size:20px;padding:6px"></i>Home</a>

            <a href="<?php 
            if (isset($_SESSION["userId"])) {
                $userid = $_SESSION["userId"];
                echo 'userChannel.php?userid=' . $userid; }
            else {
                echo 'userChannel.php?userid=usertried';
            } ?>"><i class="fa fa-user" style="color:black;font-size:20px;padding:6px"></i>My Channel</a>
        
            <a href="managePlaylists.php"><i class="fa fa-star" style="color:black;font-size:20px;padding:6px"></i>Playlists</a>

            <a href="subscriptions.php"><i class="fa fa-bookmark" style="color:black;font-size:20px;padding:6px"></i>Subscriptions</a>

            <a href="favorites.php"><i class="fa fa-thumbs-up" style="color:black;font-size:20px;padding:6px"></i>Favorites</a>

            <a href="contacts.php"><i class="fa fa-group" style="color:black;font-size:20px;padding:6px"></i>Contacts</a>

            <a href="messages.php"><i class="fa fa-comments" style="color:black;font-size:20px;padding:6px"></i>Messages</a>
        </div>
    </div> 

    <div class="logo-container">
        <img src="img/logo.png" alt="MeTube logo" height="54px">
    </div>

    <div class="searchbar">
        <form action="includes/search.inc.php" method="POST" enctype="multipart/form-data">
            <input type="search" name="search" id="search" placeholder="Search...">
            <button type="submit" name="submit"><i class="fa fa-search" style="color:black"></i></button>
        </form>
    </div>

    <div class="dropdown-settings">
        <?php
        if (isset($_SESSION["userId"])) {
            $userid = $_SESSION["userId"];
            $username = sqlSelect($link, 'userUid', 'Users', 'userId', $userid);
            echo '    
            <button class="drop-btn"><i class="fa fa-user-circle-o" style="color:black;font-size:20px;padding:7px"></i>'.$username.'</a></button>
            <div class="drop-menu settings">
                <a href="upload.php"><i class="fa fa-upload" style="color:black;font-size:20px;padding:6px"></i>Upload File</a>
                <a href="updateUser.php"><i class="fa fa-gear" style="color:black;font-size:20px;padding:6px"></i>Update Profile</a>
                <a href="includes/signout.inc.php"><i class="fa fa-sign-out" style="color:black;font-size:20px;padding:6px"></i>Sign out</a>
            </div>';
        }
        else {
            echo '
            <button class="drop-btn" style=><a href="signin.php"><i class="fa fa-sign-in" style="color:black;font-size:20px;padding:6px"></i>Sign in</a></button>';
        }
        ?>
    </div>

</div>
</head>
<body>

