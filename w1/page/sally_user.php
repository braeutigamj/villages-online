<?php
if(!isset($_GET['id'])) {
	$error = "Keine ID angegeben!";
}
$theid = $_GET['id'];
$result = $db->fetch("SELECT * FROM ally_user_list WHERE ally_id='$theid'");
$rank = 1;
while ($zeile = $result->fetch_array())
{
$playerid = $zeile['player_id'];
$player_info = $db->assoc("SELECT * FROM user WHERE id = '$playerid'");	//Player Name
$uservil = getvillages($zeile['player_id']);
if($uservil == 0) { $durch = 0; } else {
$durch = round($user['points']/$uservil); }
?>
<div id="center"><a href="game.php?village=<?= $hid ?>&page=sally&id=<?= $theid ?>">Zur Allianz</a></div>
<table class="vis" width="100%"><tr><th>Rang</th><th>Spieler</th><th>Punkte</th><th>Dörfer</th><th>Punktedurchschnitt pro Dorf</th></tr>
<tr><td><? echo $rank; ?>.</td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?php echo $zeile['player_id']; ?>"><? if($player_info['admin'] == 2) { echo "<font color='red'>"; }
elseif($player_info['admin'] == 1) { echo "<font color='green'>"; }
echo $player_info['name']; if($player_info['admin'] > 0) { echo "</font>"; } ?></a></td>
<td><? echo $player_info['points']; ?></td><td><? echo $uservil; ?></td><td><? echo $durch; ?></td>
</tr>
</table>
<?php $rank++; } ?>
