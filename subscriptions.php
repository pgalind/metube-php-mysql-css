<?php
    include_once 'header.php';

    //a user must be signed in to access their subscriptions page.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view your subscriptions.
        </div>';
	    exit();
	}
	
    $username = sqlSelect($link, 'userUid', 'Users', 'userId', $_SESSION["userId"]);    	
?>

    <div align=center>
        <h1><?php echo $username; ?>'s Subscriptions</h1><br>
        
<?php
    //get the user's subscriptions from the db.
    $result = mysqli_query($link, 'SELECT * FROM Subscriptions WHERE SubscriptionID = ' . $_SESSION["userId"] . ';');
    $subs = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($subs, $row); }
    mysqli_free_result($result);
    
    //display message if user is not subbed to anyone.
    if (count($subs) == 0) { echo "<h3>You are not currently subscribed to any MeTube channels.</h3>"; }
    
    //loop through all subs and print channel links and unsub buttons
    foreach($subs as $row) {
    
        //get username of current subscription
        $channelname = sqlSelect($link, 'userUid', 'Users', 'userId', $row['UserID']);  
    
        echo '<div class="metabox">
                  <h3><a href="./userChannel.php?userid=' . $row['UserID'] . '"> ' . $channelname . '</a></h3>
                  <form action="includes/subscriptions.inc.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" id="subid" name="subid" value="' . $row["UserID"] . '">
                      <input type="submit" style="background-color:red;" value="Unsubscribe from this User" name="remove">
                  </form>
              </div><br>';
        }    
?>        
        
    </div>

<?php
    include_once 'footer.php';
?>
