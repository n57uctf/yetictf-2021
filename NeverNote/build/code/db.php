<?php

require_once('helper.php');

$db = ConnectDatabase();
$stmt = $db->prepare("CREATE TABLE IF NOT EXISTS users(id INTEGER PRIMARY KEY, username TEXT, password TEXT, uuid TEXT)");
$stmt->execute();
?>
