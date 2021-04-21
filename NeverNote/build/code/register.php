<?php
ob_start();
require_once('header.php');
require_once('helper.php');

if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['username']) && !empty($_POST['password'])) {
    $login = stripslashes(strip_tags($_POST['username']));
    $password = hash('sha256', $_POST['password']);
    if (IfUserExists($login)) {
        $message="<p class='down'>Error: User already exists</p>";
    } else {
        $db = ConnectDatabase();
        $uuid = GenerateSessionID($login);
        $stmt = $db->prepare("INSERT INTO users(username, password, uuid) VALUES (?, ?, ?)");        
        $result = $stmt -> execute(array($login, $password, $uuid));
        if ($result) {
            ob_clean();
            header("Location: /login.php");
            die();
        } else {
            $message = "<p class='answer'>Error. Try again.</p>";
       }
    }
}

?>

<html>
 <head>
     <title>Register</title>
 </head>
 <body>
    <div class="header">
        <div style="text-align:center">
            <h2>NoteStorage service</h2>
            <p>Registration</p>
        </div>
        <div class="current">
        </div>
    </div>
    <br><br>
    <div class="form">
        <center>
        <form method="post">
            <input type="text" id="username" name="username" placeholder="username123" style="text-align:center">
            <input type="password" id="password" name="password" placeholder="password123" style="text-align:center">
            <input type="submit" value="Register" name="Register">
        </form> 
       </center>
       <?php echo $message; ?>
 </body>
 </html>

