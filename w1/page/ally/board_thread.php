<?php
$thread_id = $_GET['id'];
$thread_top= $db->fetch("SELECT * FROM ally_board_thread WHERE id = '$thread_id'");
while ($thread_top_info = $thread_top->fetch_array()) {
if($thread_top_info['ally_id'] != $ally_id)
{
exit('<script language="JavaScript">
alert("Sie haben nicht die Rechte um auf diesen Thread zuzugreifen!")
</script>');
}
$player_name = $db->query("SELECT name FROM user WHERE id = '".$thread_top_info['player_id']."'");
?>
<p><b><?php echo $thread_top_info['thread_name']; ?></b><br />
<table width="100%" class="vis"><tr><th>Erstellt von <a href="game.php?village=<?= $hid ?>&page=splayer&id=<?php echo $player_name; ?>"><?php echo $player_name; ?></a> <?= format_date($thread_top_info['time']) ?></th></tr>
<tr><td><?php echo bbcode($thread_top_info['text']); ?></td></tr></table></p>
<?php
}
$threads = $db->fetch("SELECT * FROM ally_board_answer WHERE thread_id = '$thread_id'");	//Antworten ausgeben
echo '<table width="100%" class="vis">';
while ($db_erg = $threads->fetch_array())	//Antworten
{
$player_name = $db->query("SELECT name FROM user WHERE id = '".$db_erg['player_id']."'");
?>
<tr><th>Antwort von <a href="game.php?village=<?= $hid ?>&page=splayer&id=<?php echo $player_name; ?>"><?php echo $player_name; ?></a> <?= format_date($db_erg['time']) ?></th></tr>
<tr><td><?php echo bbcode($db_erg['answer']); ?></td></tr>
<?php
}
echo "</table>";
if(!isset($_POST['answer']))	//Antwort schreiben
{
?>
<form method="post" action="#"><input type="submit" name="answer" value="Antworten"></form>
<?php
}
if(isset($_POST['answer2']))
{
$theanswer = $_POST['theanswer'];
$db->no_query("UPDATE ally_board_thread SET answers=answers+1, lastanswer='$zeit' WHERE id = '$thread_id'");
$db->no_query("INSERT INTO `ally_board_answer` (`thread_id`, `player_id`, `ally_id`, `answer`, `time`) VALUES ('$thread_id', '$id', '$ally_id', '$theanswer', '$zeit')");
$no_error = true;
}
if(isset($_POST['answer']))
{
echo '<form method="post" action="#"><textarea cols="39" rows="6" id="answer" name="theanswer"></textarea><br /><input type="submit" value="Antworten" name="answer2"></form>';
echo "<script >var a = ''; showSmileys('answer');</script>";
} ?>
