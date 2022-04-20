<?php
    include_once 'header.php';
    
    //a user cannot view their own channel uless they are signed in
    if ($_GET['userid'] == 'usertried') {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be  signed in to view your channel.
        </div>';
	    exit();
	}
    
    //user's info is gathered from ?userid=... in the url
    $userid = $_GET['userid'];
    $result = mysqli_query($link, 'SELECT * FROM Users WHERE userId = ' . $userid . ';');
    $userinfo = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    $subcount = mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Subscriptions WHERE UserID = ' . $userid . ';'));
    
    //all of user's uploads are retrieved from the db
    $result = mysqli_query($link, 'SELECT * FROM Multimedia_Files WHERE UserId = ' . $userid . ';');
    $media = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($media, $row); }
    mysqli_free_result($result);
    
?>
    <div align=center>
        <h1> Welcome to <?php echo $userinfo['userUid']; ?>'s Channel </h1><br>
        
        <?php
            //subscription button only shows up if user is logged in AND not viewing their own page
            if ((isset($_SESSION["userId"]) === true) && ($_SESSION["userId"] != $userid)) {
            
                //this checks if the current logged in user is subscribed to this channel
                $subcheck = mysqli_num_rows(mysqli_query($link, 'SELECT * FROM Subscriptions WHERE SubscriptionID = ' . $_SESSION["userId"] . ' AND UserID = ' . $userid . ';'));
                
                //subscribe button displayed if user is not subbed
                if ($subcheck == 0) {
                    echo
                    '<form action="includes/userChannel.inc.php" method="POST" enctype="multipart/form-data">
                         <input type="hidden" id="userid" name="userid" value="' . $userid . '" >
                         <input type="submit" value="Subscribe" name="sub">
                     </form>';
                }
                
                //unsubscribe button displayed if user is subbed
                if ($subcheck == 1) {
                    echo
                    '<form action="includes/userChannel.inc.php" method="POST" enctype="multipart/form-data">
                         <input type="hidden" id="userid" name="userid" value="' . $userid . '" >
                         <input type="submit" value="Unsubscribe" name="unsub">
                     </form>';
                }
            }
        ?>
        
        <p> Subscriber Count: <?php echo $subcount; ?> </p><br>
        <h2> Uploaded Media: </h2>
        
        <?php 
            foreach($media as $row) {
                echo '<div class="metabox">
                          <h3><a href="./viewmedia.php?file=' . $row['Filename'] . '"> ' . $row['Title'] . '</a></h3>
                      </div><br>';
            }
        ?>
    </div>

<?php
    include_once 'footer.php';
?>
