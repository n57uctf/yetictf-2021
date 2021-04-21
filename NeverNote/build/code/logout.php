<?php

session_start();
unset($_SESSION['uuid']);
session_destroy();
header("Location: /login.php");
exit();
?> 