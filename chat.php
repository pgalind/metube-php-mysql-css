<?php
    include_once 'header.php';
    
    //a user must be signed in to view messages with a user.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view chat logs.
        </div>';
	    exit();
	}
	
    //this page cannot work properly without the GET variable
    if (isset($_GET["uid"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>Please view chat logs from the messages page.
        </div>';
	    exit();
	}

    //finding usernames of both users
    $username = sqlSelect($link, 'userUid', 'Users', 'userId', $_SESSION["userId"]);
    $otheruser = sqlSelect($link, 'userUid', 'Users', 'userId', $_GET["uid"]);

    //header indicating what chat log is being displayed
    echo "<div align=center>
              <h1>" . $username . "'s messages with " . $otheruser . "</h1>
          </div><br>";
    
    $result = mysqli_query($link, 'SELECT * FROM Messages WHERE (SenderID = ' 
     . $_SESSION["userId"] . ' AND ReceiverID = ' . $_GET["uid"] . ') OR (SenderID = ' . $_GET["uid"] . ' AND ReceiverID = ' . $_SESSION["userId"] . ') ORDER BY DateSent ASC;');
    $messages = array();
    while ($row = mysqli_fetch_assoc($result)) { array_push($messages, $row); }
    mysqli_free_result($result);
    
    //this encapsulates the messages in a scroll box.
    echo '<div class="commentsection"><br>';
    
    //printing all messages.
    foreach ($messages as $curMessage) {
        
        //username is found based on sender ID
        $username = sqlSelect($link, 'userUid', 'Users', 'userId', $curMessage["SenderID"]);
    
        //the presence of a message in the 'replyMessage' field instead of '' is what 
        //determines whether the message is indented and reply displayed.
        if ($curMessage["ReplyMessage"] != '') {
            echo '<div class="comment" style="margin-left: 25%;">
                      <h4>Replying to: </h4><p>' . $curMessage["ReplyMessage"] . '</p>';
        }
        
        else { echo '<div class="comment">'; }
        
        //rest of message is printed the same regardless of reply/ not reply.
        echo '<h3>' . $username . ' messaged on ' . $curMessage["DateSent"] . '</h3><br><h4>' . $curMessage["Message"] . '</h4>
            <div align=center>
                <form action="includes/chat.inc.php" method="POST" enctype="multipart/form-data">
                    <input type="text" name="message" id="message" placeholder="255 chars max" required>
                    <input type="hidden" name="reply" value="' . $curMessage["Message"] . '">
                    <input type="hidden" name="uid" value="' . $curMessage["SenderID"] . '">
                    <input type="submit" value="reply to this message" name="submit">
                </form>
            </div>
        </div><br>';
        
    }
    
    echo '<br></div>';
	
?>

    <div align=center>
        <h2>Send a new message to <?php echo $otheruser; ?></h2><br>
        <form action="includes/chat.inc.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="message" id="message" placeholder="255 chars max" required>
            <input type="hidden" name="reply" value="">
            <input type="hidden" name="uid" value="<?php echo $_GET["uid"]; ?>">
            <input type="submit" value="send message" name="submit">
        </form>
    </div><br>

<?php
    include_once 'footer.php';
?>
