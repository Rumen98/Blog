<?php
/**
 * Created by PhpStorm.
 * User: vis
 * Date: 26/11/18
 * Time: 12:49
 */

if (!isset($_POST['add'])) {
    $id = strip_tags($_GET['id']);

    //Показваме формата
    echo "
<h2>ADD COMENTS</h2>
<p><form method='post' action='add_comment.php'>
<p><input type='hidden' name='id' value=$id>
<p>Name: <input type='text' name='uname'> Email: <input type='text' name='email'>
<p><textarea name='txt' rows='10' cols='50'></textarea></p>
<p><input type='submit' name='add' value=\"Add Comment\"></p>
</form></p>
";

}
else {
    //Ако бутонът Add comment e натиснат, трябва да добавим коментара в таблицата
    $id = strip_tags($_POST[id]);
    $txt = strip_tags($_POST[txt]);
    $username = strip_tags($_POST[username]);
    $email = strip_tags($_POST[email]);

    $dt = time();

    include 'config.php' ;
    mysqli_connect($server,$username,$password);
    mysqli_select_db($db);

    mysqli_query("insert into comments values (0,$id,$dt, \"$uname\",\"$email\",\"$txt\")");
    echo "<h2>Thanks for your comment!</h2><p><a href='index.php'>Go back to main page</a></p>";

}