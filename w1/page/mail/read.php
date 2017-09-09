<?php
$messageid = $_GET['msid'];
if(isset($_GET['read']) and $_GET['read'] == "0")
{
	$db->no_query("UPDATE message SET readed = '1' WHERE userid='$id' AND id = '$messageid'");
}
$result = $db->fetch("SELECT * FROM message WHERE userid='$id' AND id = '$messageid'");
while ($zeile = $result->fetch_array())
{
if(isset($_POST['answer'])) {
	$db->no_query("INSERT INTO `message_answer`(`von_player`, `text`, `time`) VALUES ('".$user['name']."','".$_POST['message']."','$zeit')");
	$lastid = $db->query("SELECT id FROM message_answer ORDER BY ID DESC LIMIT 1");
	$player = $db->query("SELECT id FROM user WHERE name = '".$zeile['von_player']."'");
	$answerid = "";
	if(!empty($zeile['answerid'])) {
		$answerid .= $zeile['answerid'];
	}
	$answerid .= $lastid.",";
	$db->no_query("INSERT INTO `message`(`userid`, `betreff`, `message`, `von_player`, `time`, `answerid`) VALUES ('$player', '".$zeile['betreff']."', '".$zeile['message']."', '".$user['name']."', '".$zeile['time']."', '$answerid')");
	$db->no_query("UPDATE `message` SET `answerid` = '$answerid' WHERE id = '$messageid'");
	$db->no_query("UPDATE user SET `message` = '1' WHERE id = '$player'");
	$no_error = true;
	$return_error = "game.php?village=".$hid."&page=mail&show=read&id=1&msid=".$messageid;
}
?>
<table class="vis" width="100%">
<?
$nachricht = $zeile['message'];
$nachricht = str_replace("
", "<br />", $nachricht);

?>
<tr><th colspan="2"><?php echo $zeile['betreff']; ?></th></tr>
<tr><td colspan="2"><?php echo bbcode(wordwrap($nachricht, 61, "<br />", 0)); ?></td></tr>
<tr><th width="80%"><? echo format_date($zeile['time']); ?></th><th><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?= $zeile['von_player'] ?>"><?= $zeile['von_player'] ?></a></th></tr>
<?
if(!empty($zeile['answerid'])) {
$zeile['answerid'] = substr($zeile['answerid'], 0, -1);
$answerid = explode(",", $zeile['answerid']);
foreach($answerid as $item => $key) {
$message = $db->assoc("SELECT * FROM message_answer WHERE id = '$key'");
?>
<tr><td colspan="2"><?php echo bbcode(wordwrap($message['text'], 61, "<br />", 0)); ?></td></tr>
<tr><th width="80%"><? echo format_date($message['time']); ?></th><th><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?= $message['von_player']; ?>"><?= $message['von_player']; ?></a></th></tr>
<?
}
}
if(isset($_GET['answer'])) { ?>
<form method="post" action="#">
<tr><td colspan="2"><textarea cols="68" id="answer" rows="6" name="message">
</textarea></td></tr>
<tr><td colspan="2"><script>var a = ""; showSmileys('answer');</script></td></tr>
<tr><th colspan="2"><input type="submit" name="answer"></th></tr>
</form>
<? } ?>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=mail&show=read&msid=<?php echo $messageid; ?>&answer">Antworten</a></td></tr>
</table>
<?php } ?>
