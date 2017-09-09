<?php
if(!isset($_GET['id']))
{
$error = "Keine ID angegeben!";
}
$theid = $_GET['id'];
$result = $db->fetch("SELECT * FROM village WHERE id = '$theid'");	//Für die Übersicht
while ($zeile = $result->fetch_array())	//Infos ausgeben
{
$thename = $zeile['userid'];
$player_info = $db->assoc("SELECT * FROM user WHERE id = '$thename'");	//Player Name
$ally_id = $db->query("SELECT ally_id FROM ally_user_list WHERE player_id = '$thename'");
$ally_name = $db->query("SELECT ally_name FROM ally WHERE id = '$ally_id'");
 ?>
<p>
Dorfinformationen
<table><tr><td>
<table class="vis"><tr><th colspan="2"><?php echo $zeile['name']; ?></th></tr>
<tr><td>Koordinaten:</td><td><?php echo $zeile['x']; ?>|<?php echo $zeile['y']; ?></td></tr>
<tr><td>Punkte:</td><td><?php echo $zeile['points']; ?></td></tr>
<tr><td>Spieler:</td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?php echo $zeile['userid']; ?>"><? echo $player_info['name']; ?></a></td></tr>
<tr><td>Allianz:</td><td><a href="game.php?village=<?= $hid ?>&page=sally&id=<? echo $ally_id; ?>"><? echo $ally_name; ?></a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=map&x=<?php echo $zeile['x']; ?>&y=<?php echo $zeile['y']; ?>">Auf Karte anzeigen</a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=place&x=<?php echo $zeile['x']; ?>&y=<?php echo $zeile['y']; ?>">Truppen schicken</a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=market&show=send&x=<?php echo $zeile['x']; ?>&y=<?php echo $zeile['y']; ?>">Rohstoffe schicken</a></td></tr>
<? if($zeile['userid'] == $user['id']) { ?><tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=overview&new_vil=<? echo $zeile['id']; ?>">Dorfübersicht</a></td></tr><? } ?>
</table width="50%">
</td><td><img src="<?= $config['image_url'] ?>village.png"></td></tr></table>
</p>
<?php } ?>
