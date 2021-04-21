<?php

if (isset($_SESSION['uuid'])) {
    header("Location: /index.php");
}
session_start();
ob_start();
require_once('helper.php');
require_once('header.php');

if ($_SERVER['REQUEST_METHOD'] === "POST" && !empty($_POST['username']) && !empty($_POST['password'])){
    $login = stripslashes(strip_tags($_POST['username']));
    $password = hash('sha256', $_POST['password']);
    $db = ConnectDatabase();
    $stmt = $db->prepare("SELECT password FROM users WHERE username=:username");
    $stmt->bindValue(':username', $login);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        if ($password === $result['password']) {
            $_SESSION['uuid'] = GenerateSessionID($login);
            ob_clean();
            header("Location: /index.php");
            die();
        } else {
            $message = "<p class='answer'>Incorrect password.</p>";
        }
    } else {
        $message = "<p class='answer'>User not found.</p>";
    }
}
?>

<html>
 <head>
     <title>Sign In</title>
 </head>
 <body>
    <div class="header">
        <div style="text-align:center">
            <h2>NoteStorage service</h2>
            <p>Login</p>
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
            <input type="submit" value="Sign in" name="Sign in">
        </form> 
       </center>
       <?php echo $message; ?>
 </body>
 </html>
 