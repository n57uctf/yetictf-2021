<?php

function ConnectDatabase () {
    return new PDO('sqlite:database.db', SQLITE3_OPEN_READWRITE);
}

function IfUserExists ($login) {
    $db = ConnectDatabase();
    $stmt = $db->prepare("SELECT username FROM users WHERE username=:login");
    $stmt->bindValue(':login', $login);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) { 
        return TRUE;
    } else {
        return FALSE;
    }
}

function GenerateSessionID($param){
	return hash('md5', implode('', unpack("L", substr(hash('md5',$param),0,4)))); 
}

function CurrentUser($uuid) {
	$db = ConnectDatabase();
	$stmt = $db->prepare("SELECT username FROM users WHERE uuid=:uuid");
	$stmt->bindValue(':uuid', $uuid);
	$stmt->execute();
	$username = $stmt->fetch(PDO::FETCH_ASSOC)['username'];
	return $username;
}

function ListUser() {
	$db = ConnectDatabase();
	$stmt = $db->prepare("SELECT username FROM users LIMIT 10 OFFSET (SELECT COUNT(*) FROM users)-5;");
	$stmt->execute();
	$users = $stmt->fetchAll();
	$output = array();
	foreach ($users as $index => &$user){
		$output[$index]= "<li>" . $user['username'] . "</li>";
	}
	$output = implode('', $output);
	return $output;
}
?>
