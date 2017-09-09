<?
if(isset($_POST['vote'])) {
	if($dblogin->exist("SELECT id FROM `forum` WHERE userid = '$id'")) {
		$error = "Du hast bereits gevotet!";
	} else {
		$dblogin->no_query("INSERT INTO `forum`(`userid`) VALUES ('$id')");
		$no_error = true;
	}
}
?>
<h3>Vote für ein Forum!</h3>
<p>Bei 100 Votes wird das Forum geöffnet! Also Vote ;)<br />
Aktuelle Votes: <b><?= $dblogin->query("SELECT count(id) FROM `forum`") ?></b></p>
<? 
if($dblogin->query("SELECT count(id) FROM `forum` WHERE userid = '$id'") <= 0) { ?>
<form method="post" action="#">
	<input type="submit" name="vote" value="Vote">
</form>
<? } ?>
