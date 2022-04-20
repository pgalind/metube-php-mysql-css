<?php
    include_once 'header.php';
    
    //a user must be signed in to access the messages page.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view your messages.
        </div>';
	    exit();
	}
	
    //getting all users the signed in user has a message log with. Doing so requires checking cases where the
    //signed in user is the sender and where they are the receiver.
    $result = mysqli_query($link, "SELECT SenderID FROM Messages WHERE ReceiverID = " . $_SESSION["userId"] . ";");
    $users = array();
    while ($row = mysqli_fetch_row($result)) { array_push($users, $row[0]); }
    mysqli_free_result($result);
    $result = mysqli_query($link, "SELECT ReceiverID FROM Messages WHERE SenderID = " . $_SESSION["userId"] . ";");
    while ($row = mysqli_fetch_row($result)) { array_push($users, $row[0]); }
    mysqli_free_result($result);
    
    //this removes duplicate values from the array
    $users = array_unique($users);
?>

<div align=center>
    <h2>Send a Message</h2>
    <p>To start a new message thread with another MeTube user, enter their user ID here.<br>
       To continue an existing conversation, please click the desired message log below.</p>
       
    <form action="includes/messages.inc.php" method="POST" enctype="multipart/form-data">
        <h3>User ID of the person receiving this message: </h3>
        <input type="text" name="sendto" id="sendto" placeholder="User ID of recipient" required><br>
        <h3>Text of your message: </h3>
        <input type="text" name="message" id="message" placeholder="255 chars max" required><br><br>
        <input type="submit" value="send message" name="submit">
    </form>
    
    <p>Your User ID is: <?php echo $_SESSION["userId"] ?></p>
    <p><?php 
           //display error message, then reset
           //it so it doesn't display again upon refresh.
           if (isset($_SESSION["fail"])) { 
               echo $_SESSION["fail"]; 
               unset($_SESSION["fail"]);
               }
       ?>
    </p>
</div><br>

<?php

    foreach ($users as $curUser) {
    
        //fetch username
        $curUname = sqlSelect($link, 'userUid', 'Users', 'userId', $curUser);  
    
        echo '<div class="metabox">
                  <h3 align=center>Message log with <a href="chat.php?uid=' . $curUser . '">' . $curUname . '</a></h3>
              </div><br>';

    }

    include_once 'footer.php';
?>
