<?php
if(!isset($_GET['id'])) {
	$_GET['id'] = 0;
}
if(is_numeric($_GET['id'])) {
	$player = $db->assoc("SELECT * FROM user WHERE id = '".$_GET['id']."'");
} else {
	$player = $db->assoc("SELECT * FROM user WHERE name = '".$_GET['id']."'");
}
if($player['id'] <= 0) {
$error = "User nicht gefunden!";
} else {
$ally_id = $db->query("SELECT ally_id FROM ally_user_list WHERE player_id = '".$player['id']."'");
$ally_name = $db->query("SELECT ally_name FROm ally WHERE id = '$ally_id'");
?>
<table class="vis"><tr><td>
<table class="vis"><tr><th colspan="2"><? echo $player['name']; ?></th></tr>
<tr><td>Punkte:</td><td><? echo $player['points']; ?></td></tr>
<tr><td>Allianz:</td><td><a href="game.php?village=<?= $hid ?>&page=sally&id=<? echo $ally_id; ?>"><? echo $ally_name; ?></a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=mail&show=new&at=<? echo $player['name']; ?>">Nachricht schreiben</a></td></tr>
<? if($db->query("SELECT count(id) FROM `friends` WHERE (`user1` = '".$player['id']."' AND `user2` = '$id') OR (`user2` = '".$player['id']."' AND `user1` = '$id')") <= 0 and $id != $player['id']) { ?><tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=friends&add=<? echo $player['name']; ?>">Als Freund hinzufügen</a></td></tr><? } ?>
<tr><th colspan="2">Dörfer:</th></tr>
<?
$result = $db->fetch("SELECT id,name FROM village WHERE userid = '".$player['id']."'");
while ($villages = $result->fetch_array()) {
?>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $villages['id']; ?>"><? echo $villages['name']; ?></a></td></tr>
<? } ?>
</table>
<? } ?>
</td>
<td style="vertical-align:top;">
<table>
<? if(!empty($player['logo'])) { ?><tr><th>Logo</th></tr>
<tr><td><img src="upload/<?php echo $player['logo']; ?>"></td></tr><? } ?>
<? if(!empty($player['ptext'])) { ?><tr><th>Beschreibung</th></tr>
<tr><td><?php echo bbcode($player['ptext']); ?></td></tr><? } ?>
</table>
</td></tr></table>
