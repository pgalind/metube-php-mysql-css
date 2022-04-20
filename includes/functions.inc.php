<?php

/* --------------------------------------------------- Error-handling functions --------------------------------------------------- */

// STATUS: WORKING
// Preconditions: pass uid in the signup form
function invalidUid($uid) {
    $result;
    if (!preg_match("/^[a-zA-Z0-9]*$/", $uid) || strlen($uid) < 6 || strlen($uid) > 16) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

// STATUS: WORKING
// Preconditions: pass email in the signup form
function invalidEmail($email) {
    $result;
    // FILTER_VALIDATE_EMAIL is a built-in filter in PHP
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

// STATUS: WIP
// Preconditions: pass the current username from $_SESSION, current password from updateUser form; pass SQL connection
function wrongPwd($link, $uid, $pwd) {
    $result;
    $uidExists = uidExists($link, $uid, $uid); // function returns entire row from db table
    // verify password
    $hashedPwd = $uidExists["userPwd"];
    $checkPwd = password_verify($pwd, $hashedPwd);
    if ($checkPwd === false) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

// STATUS: WORKING
// Preconditions: pass password in signup form
function invalidPwd($pwd) {
    $result;
    if (strlen($pwd) < 6 || strlen($pwd) > 125) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

// STATUS: WORKING
// Preconditions: pass password and password confirm in signup form
function pwdMatch($pwd, $pwdconfirm) {
    $result;
    if ($pwd !== $pwdconfirm) {
        $result = true;
    }
    else {
        $result = false;
    }
    return $result;
}

// STATUS: WORKING
// Preconditions: pass username, email from signup form; pass SQL connection
function uidExists($link, $uid, $email) {

    $query = "SELECT * FROM Users WHERE userUid = ? OR userEmail = ?;";
    // use prepared statement to protect against SQL injection attacks
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    // "ss" because there are 2 strings
    mysqli_stmt_bind_param($stmt, "ss", $uid, $email);
    mysqli_stmt_execute($stmt);
    $resultData = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultData)) {
        return $row;
    }
    else {
        $result = false;
        return $result;
    }

    mysqli_stmt_close($stmt);
}

/* --------------------------------------------------- SQL query functions --------------------------------------------------- */

// STATUS: WORKING
// Preconditions: pass name, username, email, and password from signup form; pass SQL connection
function createUser($link, $name, $uid, $email, $pwd) {

    //SQL statement to be completed
    $query = "INSERT INTO Users (userFullName, userUid, userEmail, userPwd, CreationDate) VALUES (?, ?, ?, ?, ?);";
    
    // use prepared statements
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    // encrypt user password
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
    
    //finding current date and time
    $date = new DateTime();
    $creationDate = date_format($date, 'Y-m-d-H-i-s');

    // "sssss" because there are 5 strings
    mysqli_stmt_bind_param($stmt, "sssss", $name, $uid, $email, $hashedPwd, $creationDate);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../signup.php?error=none");
}

// STATUS: WORKING
// Preconditions: pass username/email and password from login form; pass SQL connection
function loginUser($link, $uid, $pwd) {

    // verify username/email
    $uidExists = uidExists($link, $uid, $uid); // if user exists, function returns entire row from db table
    if ($uidExists === false) {
        header("location: ../signin.php?error=wronglogin");
        exit();
    }
    // verify password
    $hashedPwd = $uidExists["userPwd"];
    $checkPwd = password_verify($pwd, $hashedPwd);
    if ($checkPwd === false) {
        header("location: ../signin.php?error=wronglogin");
        exit();
    }
    // if username and password are correct, start a session for the user
    else if ($checkPwd === true) {
        session_start();
        $_SESSION["userId"] = $uidExists["userId"];
        header("location: ../index.php");
    }
}

// STATUS: WIP
// Preconditions: pass name, username, email, and password from updateUser form ; pass SQL connection
function updateUser($link, $id, $name, $uid, $email, $pwd) {
    //SQL statement to be completed
    $query = "UPDATE Users SET userFullName = ?, userUid = ?, userEmail = ?, userPwd = ? WHERE userId = ?;";
    
    // use prepared statements
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        header("location: ../updateUser.php?error=stmtfailed");
        exit();
    }

    // encrypt password
    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss", $name, $uid, $email, $hashedPwd, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../updateUser.php?error=none");
}

//STATUS: WORKING
//Preconditions: pass a valid filename
function getMediaType ($filename) {

    //file extension is retrieved from filename
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    
    //type is image if extension matches any of these most common image formats
    if ($ext === 'png' || $ext === 'svg' || $ext === 'jpg' || $ext === 'jpeg' || $ext === 'gif' || $ext === 'apng') { return 'IMG'; }
    
    //type is video if extension matches these supported video formats
    else if ($ext === 'mp4' || $ext === 'ogg' || $ext === 'webm') { return 'VID'; }
    
    //recognized audio types
    else if ($ext === 'wav' || $ext === 'mp3') { return 'AUD'; }
    
    //if nothing matches, return an error
    else { return 'ERR'; }
}

// STATUS: WORKING
// Preconditions: SQL database connection, user ID, title, description, keywords, and filename associated with file
function insertMedia ($link, $uid, $title, $description, $keywords, $filename) {

    //SQL statement to be completed
    $query = "INSERT INTO Multimedia_Files (UserId, Title, Description, Keywords, Upload_Date, Filename) VALUES (?, ?, ?, ?, ?, ?);";
    
    // use prepared statements
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        header("location: ../upload.php?error=stmtfailed");
        exit();
    }
    
    //finding current date and time
    $date = new DateTime();
    $uploadDate = date_format($date, 'Y-m-d-H-i-s');
    
    // "isssss" for the 6 values to be used
    mysqli_stmt_bind_param($stmt, "isssss", $uid, $title, $description, $keywords, $uploadDate, $filename);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../upload.php?error=none");
}

//STATUS: WORKING
//Preconditions: valid database link and comment information
function insertComment ($link, $filename, $FileID, $senderID, $comment) {
    
    //finding current date and time
    $date = new DateTime();
    $creationDate = date_format($date, 'Y-m-d-H-i-s');
    
    //SQL statement to be completed
    $query = "INSERT INTO Comments (FileID, SenderID, DatePosted, CommentText) VALUES (?, ?, ?, ?);";
    
    // use prepared statements
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo "Something went wrong";
        exit();
    }
    
    // "ssss" for the 4 values to be used
    mysqli_stmt_bind_param($stmt, "ssss", $FileID, $senderID, $creationDate, $comment);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

}

//STATUS: WORKING
//Preconditions: valid database link and values for "Select blank from blank where blank = blank" query.
function sqlSelect ($link, $field, $table, $field2, $value) {
    
    //SQL statement to be completed
    $query = "SELECT " . $field . " FROM " . $table . " WHERE " . $field2 . " = '" . $value . "';";
 
    $result = mysqli_query ($link, $query);
    $final = mysqli_fetch_row ($result);
    mysqli_free_result($result);

    //returing final[0] gives the first value of the rows retrieved. Since this function is designed to get one column value
    //from one row, this is an okay assumption to make.
    return $final[0];
}