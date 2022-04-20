<?php
    include_once 'header.php';
?>

<div class="box">
    <div class="logoBox">
        <img src="img/logo.png" alt="MeTube logo" height="100px">
    </div>
    <h2>Sign Up</h2>
    <form action="includes/signup.inc.php" method="POST" class="login-form" autocomplete="off" enctype="multipart/form-data">
        <div class="inputBox">
            <input type="text" name="name" placeholder="Full name" required>
        </div>
        <div class="inputBox">
            <input type="text" name="uid" placeholder="Username" required>
        </div>
        <div class="inputBox">
            <input type="text" name="email" placeholder="Email" required>
        </div>
        <div class="inputBox">
            <input type="password" name="pwd" placeholder="Password" required>
        </div>
        <div class="inputBox">
            <input type="password" name="pwdconfirm" placeholder="Confirm password" required>
        </div>
        <button type="submit" name="submit"><i class="fa fa-edit" style="color:black;font-size:16px;padding:3px 6px"></i>Sign up</button>
    </form>
</div>

<?php
    // print error messages if errors appear in HTTP header
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "invalidusername") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Username must be 6-16 characters long, and can only contain a-z, A-Z, and 0-9.
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
            header("location: ./signin.php");
        }
    }
?>

<?php
    include_once 'footer.php';
?>