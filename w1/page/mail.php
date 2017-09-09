<div id="center"><p><b>Nachrichten</b><br />
Hier kannst du Nachrichten zu anderen Spielern senden!</p>
<p><a href="game.php?village=<?= $hid ?>&page=mail">Nachrichten lesen</a> - <a href="game.php?village=<?= $hid ?>&page=mail&show=new">Nachricht schreiben</a></p><hr>
</div>
<?php
if($user['message'] == 1) {
	$db->no_query("UPDATE user SET message = 0 WHERE id = '".$user['id']."'");
}
if(isset($_GET['show']))	//Wenn Seite gewählt
{
	$file="page/mail/".$_GET['show'].".php";
	if (file_exists($file)) {
		include $file;
	} else {
		include "page/mail/overview.php";
	}
} else //Default seite
{
	include "page/mail/overview.php";
}
?>
