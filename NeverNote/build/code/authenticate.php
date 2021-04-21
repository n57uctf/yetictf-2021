<?php

session_start();
if(empty($_SESSION["uuid"])) {
	header('Location: login.php');
	die();
session_destroy();
die();
}
?>