<?php
if(!isset($_GET['id'])) {
	$error = "Keine ID angegeben!";
}
$theid = $_GET['id'];
$result = $db->fetch("SELECT * FROM ally WHERE id = '$theid'");	//Für die Übersicht
while ($zeile = $result->fetch_array())	//Infos ausgeben
{
?>
<table class="vis"><tr><td>
<table class="vis"><tr><th colspan="2"><?php echo $zeile['ally_name']; ?></th></tr>
<tr><td>Allianzname:</td><td><?php echo $zeile['ally_name']; ?></td></tr>
<tr><td>Allianzk&uuml;rzel:</td><td><?php echo $zeile['ally_short']; ?></td></tr>
<tr><td>Mitgliederzahl:</td><td><?php echo $db->query("SELECT COUNT(*) FROM ally_user_list WHERE ally_id='$theid'"); ?></td></tr>
<? if(!empty($zeile['homepage'])) { ?><tr><td>Allianzhomepage:</td><td><b><a target="_blank" href="<?= $config['url'] ?>?page=redir&url=<?php echo $zeile['homepage']; ?>"><?php echo $zeile['homepage']; ?></a></b></td></tr><? } ?>
</table>
<b><a href="game.php?village=<?= $hid ?>&page=sally_user&id=<?php echo $theid; ?>">Mitglieder</a></b>
</td>
<td style="vertical-align:top;">
<table>
<? if(!empty($zeile['logo'])) { ?><tr><th>Logo</th></tr>
<tr><td><img src="upload/<?php echo $zeile['logo']; ?>"></td></tr><? } ?>
<? if(!empty($zeile['beschr'])) { ?><tr><th>Beschreibung</th></tr>
<tr><td><?php echo bbcode($zeile['beschr']); ?></td></tr><? } ?>
</table>
<?php } ?>
</td></tr></table>
