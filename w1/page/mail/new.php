<?php
$atplayer = "";
if(isset($_GET['at']))
{
	$atplayer = $_GET['at'];
}
if(isset($_GET['betreff'])) { 
	$betreff = $_GET['betreff']; 
} else {
	$betreff="";
}
if(isset($_GET['message'])) { 
	$message = $_GET['message']; 
} else {
	$message="";
}
if(isset($_POST['message']))
{
	if($_POST['player'] == "")
	{
		$error = "Du must einen Empfaenger angeben!";
	}
	else
	{
		$player = $_POST['player'];
		$player = $db->query("SELECT id FROM user WHERE name = '$player'");
		$playerv = $db->query("SELECT name FROM user WHERE id = '$id'");
		$betreff = $_POST['betreff'];
		$message = $_POST['message'];
		$db->no_query("INSERT INTO `message` (`userid`, `von_player`, `betreff`, `message`, `readed`, `time`) VALUES ('$player', '$playerv', '$betreff', '$message', '0', '$zeit');");
		$db->no_query("UPDATE user SET message = 1 WHERE id = '$player'");
		$no_error = "Nachricht wurde versendet!";
	}
}
?>
<form method="post" action="#">
<table class="vis" width="100%">
<tr><td>An:</td><td><input type="text" value="<?php echo $atplayer; ?>" name="player"></td></tr>
<tr><td>Betreff:</td><td><input type="text" value="<?php echo $betreff; ?>" name="betreff"></td></tr>
<tr><td colspan="2"><textarea id="answer" cols="70" rows="21" name="message"><?= $message; ?>
</textarea></td></tr>
<tr><td colspan="2"><script>var a = ""; showSmileys('answer');</script></td></tr>
<tr><td colspan="2"><input type="submit"></td></tr>
</table>
</form>
