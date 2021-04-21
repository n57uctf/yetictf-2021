<?php
require_once('authenticate.php');
require_once('helper.php');
require_once('note.php');
require_once('header.php');
$uuid = $_SESSION['uuid'];
$username = CurrentUser($uuid);
?>

<html>
 <head>
     <title>Index</title>
 </head>
 <body>
    <div class="header">
        <div style="text-align:center">
            <h2>NoteStorage service</h2>
            <p>We will keep your secrets</p>
        </div>
        <div class="current">
            <p style="text-align:left"> Hello, <?php echo $username ?> </p>
        </div>
    </div>

    <div class="list">
        <label class="label">You notes:</label>
        <ol class="square">
            <?php 
            echo ListNote($username); ?>
        </ol>
    </div>

    <div class="list">
        <label class="label">Last 5 register users:</label>
        <ol class="square">
            <?php echo ListUser();?>
        </ol>
    </div>

    <div class="area">
        <form method="post">
            <label class="label" for="add">Add note</label>
            <textarea id="note" name="note" placeholder="Write something.." style="height:100px"></textarea>
            <input type="submit" value="Add" name="Add">
        </form>
        <?php
        if (isset($_POST['Add'])) {
            if (empty($_POST['note'])) {
                echo "<p class='answer'>Error: Note is empty</p>";
        } else {
        $new_note = $_POST['note'];
        $result = AddNote($new_note, $username);
        echo "<p class='answer'>" . $result . "</p>";
        }}?>
    </div>

    <div class="area">
        <form method="post">
            <label class='label' for="read">Read note</label>
            <textarea id="file" name ="file" placeholder="Type you note filename.." style="height:100px"></textarea>
            <input type="submit" value="Read" name="Read">
         </form>
         <?php 
        if (isset($_POST['Read'])) {
            if (empty($_POST['file'])) {
                echo "<p class='answer'>Error: Filename is empty</p>";
            } else {
                $file = $_POST['file'];
            $data = ReadNote($username, $file);
            echo "<p class='answer'>" . $data . "</p>";
            }}?>

    </div>    
</body>
</html>
