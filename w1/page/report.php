<div  id="center"><p><b>Berichte</b><br />
Hier kannst du alle deine Berichte einsehen!</p>
<a href="game.php?village=<?= $hid ?>&page=report">Alle Berichte</a> - <a href="game.php?village=<?= $hid ?>&page=report&show=attack">Angriffe</a> - <a href="game.php?village=<?= $hid ?>&page=report&show=market">Marktplatz</a> - <a href="game.php?village=<?= $hid ?>&page=report&show=ally">Allianz</a> - <a href="game.php?village=<?= $hid ?>&page=report&show=friend">Freunde</a>
<hr>
<?php
if($user['report'] == 1) {
	$db->no_query("UPDATE user SET report = 0 WHERE id = '".$user['id']."'");
}
if(isset($_GET['show']) and $_GET['show'] == "view")	//Wenn Seite gewählt
{
	include "page/report/view.php";
}
else
{
	include "page/report/overview.php";
}
?>
