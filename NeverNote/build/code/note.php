<?php

function ListNote($username) {
    $dir = __DIR__."/notes/".$username."/";
    if (!is_dir($dir)) { 
        return "Notes not found!";
    }
    $notes = array_diff( scandir($dir), array(".", "..") );
    foreach ($notes as $index=>&$note){
        $output[$index]= "<li>" . $note . "</li>";
    }
    $output = implode('', $output);
    return $output;
}

function ReadNote($username, $filename){
    $content = @file_get_contents("./notes/".$username."/".$filename);
    if($content === FALSE) {
        return "Error: It seems that such a record does not exist...";
    } else {
        return "You note: ".$content;
    }
}

function AddLog($username, $filename, $new_note) {
    $dir = __DIR__."/log/NoteLog.log";
    $message = "User ".$username." added new note ".$new_note.", saved in file ".$filename .PHP_EOL;
    file_put_contents($dir, $message, FILE_APPEND | LOCK_EX);
}

function AddNote($new_note, $username) {
    try {
        $dir = __DIR__."/notes/".$username."/";
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $filename = rand(100000, 1000000000);        
            file_put_contents($dir.$filename, $new_note, FILE_APPEND | LOCK_EX);
            Addlog($username, $filename, $new_note);
        $message = "Stored. You note: " . $filename;
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
    return $message;
}
?>
