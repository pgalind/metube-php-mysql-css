<?php
    include_once 'header.php';
    
    //a user must be signed in to access the contacts page.
    if (isset($_SESSION["userId"]) === FALSE) {
        echo '
        <div class="alert">
            <span class="closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span>
            <i class="fa fa-warning" style="color:white"></i>You must be signed in to view contacts.
        </div>';
	    exit();
	}
?>

<div align=center>
    <h2>Add a New Contact</h2>
    <p>Have the person desired as a contact tell you their User ID. 
    <br>Enter their User ID here, and they will be registered as a contact. 
    <br>Information about your contacts will be displayed on this page.
    </p>
    
    <form action="includes/contacts.inc.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="contactid" id="contactid" placeholder="User ID of your contact" required>
        <input type="submit" value="Add Contact" name="submit" required>
        <br>
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
</div>

<?php

    //info about all the contacts for the current user is collected.
    $contacts = mysqli_query($link, 'SELECT ContactID FROM Contacts WHERE UserID = ' . $_SESSION["userId"] . ';');
    $contactarray = array();
    while ($row = mysqli_fetch_assoc($contacts)) { array_push($contactarray, $row); }
    mysqli_free_result($contacts);
    
    //message displayed if user has no contacts.
    if (count($contactarray) == 0) { echo '<h3 align=center>You have no contacts.</h3>'; }
    
    //loops until all contacts have been printed.
    foreach($contactarray as $row) {
        $fetch = mysqli_query($link, 'SELECT * FROM Users WHERE userId = ' . $row["ContactID"] . ';');
        $contact = mysqli_fetch_assoc($fetch);
        mysqli_free_result($fetch);
        
        echo '<div class="metabox">
                  <h3 align=center>Name: ' . $contact["userFullName"] . '<br>
                      User Name: ' . $contact["userUid"] . '<br>
                      E-mail: ' . $contact["userEmail"] . '<br>
                  </h3>
                  <form align=center action="includes/contacts.inc.php" method="POST" enctype="multipart/form-data">
                      <input type="hidden" id="delete" name="delete" value="' . $contact["userId"] . '">
                      <input type="submit" value="Click to Delete from Contacts" name="submit">
                  </form><br>
              </div><br>';
    }

?>


<?php
    include_once 'footer.php';
?>
