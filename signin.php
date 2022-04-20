<?php
    include_once 'header.php';
?>

<div class="box">
    <div class="logoBox">
        <img src="img/logo.png" alt="MeTube logo" height="100px">
    </div>
    <h2>Sign In</h2>
    <form action="includes/signin.inc.php" method="POST" class="signin-form" enctype="multipart/form-data">
        <div class="inputBox">
            <input type="text" name="uid" placeholder="Username/email" required>    
        </div>
        <div class="inputBox">
            <input type="password" name="pwd" placeholder="Password" required>
        </div>
        <button type="submit" name="submit"><i class="fa fa-sign-in" style="color:black;font-size:16px;padding:3px 6px"></i>Sign in</button>
    </form>

    <p>Don't have a MeTube account yet?</p>
    <form action="signup.php">
        <button type="submit"><i class="fa fa-edit" style="color:black;font-size:16px;padding:3px 6px"></i>Sign up</button>
    </form>
</div>

<?php
    // print error messages if errors appear in HTTP header
    if (isset($_GET["error"])) {
        if ($_GET["error"] == "wronglogin") {
            echo '
            <div class="alert">
                <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
                <i class="fa fa-warning" style="color:white"></i>Invalid username or password. Try again!
            </div>
            ';
        }
    }
?>

<?php
    include_once 'footer.php';
?>