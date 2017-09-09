<?
include "incl/function.php";
include "config.php";
$db = new db('w1');
$requests = sercurity($_GET, $_POST, $_REQUEST);
$_GET = $requests['get'];
$_POST = $requests['post'];
$_REQUEST = $requests['request'];
if(isset($_GET['map']) and isset($_GET['id'])) {
	$village = $db->assoc("SELECT userid, name, x, y, points FROM `village` WHERE id = '".$_GET['id']."'");
	$ally_id = $db->query("SELECT ally_id FROM ally_user_list WHERE player_id = '".$village['userid']."'");
	$ally_name = $db->query("SELECT ally_name FROM ally WHERE id = '$ally_id'");
?>
<table><tr><th colspan="2"><a href="game.php?village=<?= $hid ?>&page=smap&id=<?= $_GET['id'] ?>"><?= $village['name']." (".$village['x']."|".$village['y'] ?>)</a></th></tr>
<tr><td>Punkte</td><td><?= $village['points'] ?></td></tr>
<? if($village['userid'] != "-1") { ?><tr><td>Spieler</td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?= $village['userid'] ?>"><?= $db->query("SELECT `name` FROM `user` WHERE id = '".$village['userid']."'") ?></a></td></tr>
<tr><td>Allianz</td><td><a href="game.php?village=<?= $hid ?>&page=sally&id=<?= $ally_id ?>"><?= $ally_name ?></a></td></tr><? } ?>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=map&x=<?php echo $village['x']; ?>&y=<?php echo $village['y']; ?>">zentrieren</a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=place&x=<?php echo $village['x']; ?>&y=<?php echo $village['y']; ?>">Truppen schicken</a></td></tr>
<tr><td colspan="2"><a href="game.php?village=<?= $hid ?>&page=market&show=send&x=<?php echo $village['x']; ?>&y=<?php echo $village['y']; ?>">Rohstoffe versenden</a></td></tr>
</table>
<?
}
?>
