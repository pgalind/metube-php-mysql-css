<?php
    include_once 'header.php';

    //form can only be accessed for logged in users.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to update user information.
        </div>';
        exit();
    }
    
    $userid = $_SESSION["userId"];
    $username = sqlSelect($link, 'userUid', 'Users', 'userId', $userid);
    $userInfo = uidExists($link, $username, $username);
?>

<div class="box">
    <div class="logoBox">
        <img src="img/logo.png" alt="MeTube logo" height="100px">
    </div>
    <h2>Update Profile</h2>
    <form action="includes/updateUser.inc.php" method="POST" class="login-form" autocomplete="off" enctype="multipart/form-data">
        <div class="inputBox">
            <input type="text" name="name" value="<?php echo $userInfo['userFullName']; ?>" required>
        </div>
        <div class="inputBox">
            <input type="text" name="uid" value="<?php echo $userInfo['userUid']; ?>" required>
        </div>
        <div class="inputBox">
            <input type="text" name="email" value="<?php echo $userInfo['userEmail']; ?>" required>
        </div>
        <div class="inputBox">
            <input type="password" name="cur_pwd" placeholder="Current password" required>
        </div>
        <div class="inputBox">
            <input type="password" name="pwd" placeholder="New password" required>
        </div>
        <div class="inputBox">
            <input type="password" name="pwdconfirm" placeholder="Confirm new password" required>
        </div>
        <button type="submit" name="submit"><i class="fa fa-gear" style="color:black;font-size:16px;padding:3px 6px"></i>Update</button>
    </form>
</div>

<?php
    // display error messages if errors appear in HTTP header
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "invalidusername") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>usename must be at least 6 characters long, can can only contain a-z, A-Z, and 0-9.
            </div>
            ';
        }
        else if ($_GET["error"] == "invalidemail") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Enter a valid email address.
            </div>
            ';
        }
        else if ($_GET["error"] == "wrongpassword") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Your current password is wrong.
            </div>
            ';
        }
        else if ($_GET["error"] == "invalidpassword") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Password must be at least 6 characters long.
            </div>
            ';
        }
        else if ($_GET["error"] == "passwordsdontmatch") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Passwords don\'t match.
            </div>
            ';          
        }
        else if ($_GET["error"] == "useralreadyexists") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>An account already exists with that username or email.
            </div>
            ';
        }
        else if ($_GET["error"] == "stmtfailed") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Something went wrong. Try again!
            </div>
            ';
        }
        else if ($_GET["error"] == "none") {
            sleep(1);
            header("location: ./index.php");
        }
    }
?>

<?php
    include_once 'footer.php';
?>