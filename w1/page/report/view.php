<?php
$reportid = $_GET['msid'];
if(isset($_GET['read']) and $_GET['read'] == "0")
{
  $db->no_query("UPDATE `report` SET readed = '1' WHERE userid='$id' AND id = '$reportid'");
}
$report = $db->assoc("SELECT * FROM report WHERE id = '$reportid'");
if($report['id'] > 0 and ($report['userid'] == $id || $user['admin'] == 2 || (isset($_GET['code']) and $_GET['code'] == $report['code']))) {
  $nacher = $db->query("SELECT id FROM report WHERE userid = '".$user['id']."' AND id < '".$_GET['msid']."' ORDER BY id DESC LIMIT 1");
  $vorher = $db->query("SELECT id FROM report WHERE userid = '".$user['id']."' AND id > '".$_GET['msid']."' LIMIT 1");
  if(isset($_GET['do']) and $_GET['do'] == "delete") {
    $db->no_query("DELETE FROM report WHERE id = '$reportid' AND userid='$id'");
    $return_error = "game.php?village=".$hid."&page=report";
  }
  if(isset($_GET['do']) and $_GET['do'] == "puplic") {
    $pupliccode = md5("start".spezielstring(11)."code".$reportid."end");
    $db->no_query("UPDATE `report` SET code = '$pupliccode' WHERE id = '$reportid'");
    echo "Über folgenden Link kann jeder auf diesen Bericht zugreifen!<br /><input type='text' size='90' value='".$config['w1']."game.php?village=".$hid."&page=report&show=view&msid=287&code=".$pupliccode."' />";
  }
?>
<table class="vis" id="center" width="400px">
<tr><th width="20%"><? if($vorher != "") { ?><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $vorher; ?>&read=0"><<</a><? } ?></th><th width="30%"><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $reportid; ?>&do=delete">Löschen</a></th><th width="30%"><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $reportid; ?>&do=puplic">Veröffentlichen</a></th><th width="20%"><? if($nacher != "") { ?><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $nacher; ?>&read=0">>></a><? } ?></th></tr>
<tr><td colspan="4">
<h3><?php echo message_type($report['type']); ?></h3>
<?
if($report['type'] == "attack_lost" || $report['type'] == "attack_won") {
$village_attack = $db->assoc("SELECT id,name,userid FROM village WHERE id = '".$report['att_dorf']."'");
$attackername = $db->query("SELECT name FROM user WHERE id = '".$village_attack['userid']."'");
$village_deffender = $db->assoc("SELECT id,name,userid FROM village WHERE id = '".$report['deff_dorf']."'");
if($village_deffender['userid'] != "-1") {
$deffendername = $db->query("SELECT name FROM user WHERE id = '".$village_deffender['userid']."'");
} else {
$deffendername = "";
}
?>
<table class="vis" id="center">
<tr><th>Angreifendes Dorf:</th><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $village_attack['id']; ?>"><? echo $village_attack['name']; ?></a></td></tr>
<tr><th>Angreifender Spieler:</th><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<? echo $village_attack['userid']; ?>"><? echo $attackername; ?></a></td></tr>
<tr><th>Verteidigendes Dorf:</th><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $village_deffender['id']; ?>"><? echo $village_deffender['name']; ?></a></td></tr>
<tr><th>Verteidigender Spieler:</th><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<? echo $village_deffender['userid']; ?>"><? echo $deffendername; ?></a></td></tr>
</table>
<table class="vis" id="center">
<tr><th colspan="13">Angreifer</th></tr>
<tr><th></th>
<?
foreach($units->name as $item => $key) {
?>
<th><img src="<?= $config['image_url'] ?>unit/<? echo $item; ?>.png"></th>
<? } ?></tr>
<tr><td>Einheiten</td>
<?
$report['att'] = unserialize($report['att']);
foreach($units->name as $item => $key) {
?>
<td><? echo $report['att'][$item]; ?></td>
<? } ?></tr>
<tr><td>Verluste</td>
<?
$report['att_lost'] = unserialize($report['att_lost']);
foreach($units->name as $item => $key) {
?>
<td><? echo $report['att_lost'][$item]; ?></td>
<? } ?></tr>
<tr><th colspan="13">Verteidiger</th></tr>
<tr><th></th>
<?
foreach($units->name as $item => $key) {
?>
<th><img src="<?= $config['image_url'] ?>unit/<? echo $item; ?>.png"></th>
<? } ?></tr>
<tr><td>Einheiten</td>
<?
if($report['deff'] != "N;") {
$report['deff'] = unserialize($report['deff']);
foreach($units->name as $item => $key) {
?>
<td><? if($report['deff'] != "N;") { echo $report['deff'][$item]; } else { echo "0"; } ?></td>
<? }
} else {
  echo "<td colspan='11'>Es konnten keine Informationen gesammelt werden.</td>";
}
?></tr>
<? if($report['deff'] != "N;") { ?>
<tr><td>Verluste</td>
<?
if($report['deff_lose'] != "N;") {
$report['deff_lose'] = unserialize($report['deff_lose']);
}
foreach($units->name as $item => $key) {
?>
<td><? if($report['deff_lose'] != "N;") { echo $report['deff_lose'][$item]; } else { echo "0"; } ?></td>
<? } ?></tr> <? } ?>
</table>
<?
if(!empty($report['wall']) and ($wall[0] != $wall[1])) {
  $wall = explode(",", $report['wall']);
  echo "Wall wurde von Stufe <b>".$wall[0]."</b> auf Stufe <b>".$wall[1]."</b> reduziert!<br />";
}
if(!empty($report['what'])) {
  $what = explode(",", $report['what']);
  echo $building->de[$what[0]]." wurde von Stufe <b>".$what[1]."</b> auf Stufe <b>".$what[2]."</b> reduziert!<br />";
}
if(!empty($report['agreement'])) {
  $agreement = explode(",", $report['agreement']);
  echo "Zustimmung wurde von <b>".$agreement[0]."</b> auf <b>".$agreement[1]."</b> gesenkt!<br />";
}
} elseif($report['type'] == "drop_out" || $report['type'] == "al_delete") {
echo '<a href="game.php?village='.$hid.'&page=ally"> Zu der Allianzübersicht!</a>';
} elseif($report['type'] == "friend_delete" || $report['type'] == "friend_get" || $report['type'] == "friend_accept") {
echo '<a href="game.php?village='.$hid.'&page=friends">Zur Freundesliste</a>';
} elseif($report['type'] == "uv") {
echo "<a href='game.php?village='.$hid.'&page=settings'>Zur Urlaubsvertrettungseinstellungen</a>";
} else {
$booty = explode(",", $report['booty']);
?>
<table class="vis">
<tr><th>Von Dorf</th><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<?= $report['att'] ?>"><?= $db->query("SELECT `name` FROM `village` WHERE id = '".$report['att']."'") ?></a></td></tr>
<tr><th>Nach Dorf</th><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<?= $report['deff'] ?>"><?= $db->query("SELECT `name` FROM `village` WHERE id = '".$report['deff']."'") ?></a></td></tr>
</table>
<table class="vis"><tr><th><img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"></th><th><img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"></th><th><img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"></th><th><img src="<? echo $config['image_url']; ?>res/gold.png" width="25px"></th></tr>
<tr><td><? echo $booty[0]."</td><td>".$booty[1]."</td><td>".$booty[2]."</td><td>".$booty[3]; ?></td></tr>
</table>
<? } ?>
</td></tr>
<tr><th width="20%"><? if($vorher != "") { ?><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $vorher; ?>&read=0"><<</a><? } ?></th><th width="30%"><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $reportid; ?>&do=delete">Löschen</a></th><th width="30%"><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $reportid; ?>&do=puplic">Veröffentlichen</a></th><th width="20%"><? if($nacher != "") { ?><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<? echo $nacher; ?>&read=0">>></a><? } ?></th></tr></table>
<?
} else { $error = "Du kannst nur deine eigenen Berichte einsehen!";
} ?>
