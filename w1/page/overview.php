<?
//Dorfname ändern
if(isset($_POST['haven_change'])) {
	$db->no_query("UPDATE village SET name = '".$_POST['village_name']."' WHERE id = '".$_SESSION['hid']."'");
	$no_error = "Dein Dorf wurde in ".$_POST['village_name']." umgetauft!";
}
//Dorfübersicht
if(isset($_GET['style'])) {
	if($_GET['style'] == 1) { $style = 0; } else { $style = 1; }
	$db->no_query("UPDATE user SET village_style = '$style' WHERE id = '$id'");
	$no_error = true;
}
$result = $db->fetch("SELECT * FROM movement WHERE to_village='$hid'");
if($db->query("SELECT count(id) FROM movement WHERE to_village='$hid' LIMIT 1") > 0) {
	echo "<table class='vis' width='100%'><tr><th width='8%'>Ankommende Truppen</th><th>Von Dorf</th><th>Ankunft</th></tr>";
	while ($ank = $result->fetch_array()) {
		$fromv = $db->assoc("SELECT name,x,y FROM village WHERE id = '".$ank['from_village']."'");
		$loadtime[] = $ank['end_time'];
?>
<tr><td><img src="<?= $config['image_url'] ?>fight/<? echo $ank['type']; ?>.png"></td><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $ank['from_village']; ?>">
<? echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td id="<?= $ank['end_time'] ?>"></td></tr>
<?
	}
	echo "</table>";
} ?>
Dorfübersicht von <b><a href="?page=overview"><? echo $village['name']." (".$village['x']."|".$village['y']; ?>)</a></b>
<table width="700px" class="table"><tr><td width="500px">
<?php	//Dorf
if($user['village_style'] == 0) {	//Grafische Dorfübersicht?
	echo '<div style="position:relative;"><img src="'.$config['image_url'].'village/bg.png">';
	$bbuildi = array_reverse($building->name);
	foreach($building->name as $item => $key) {
		if($village[$item] > 0) {
			echo '<a href="game.php?village='.$hid.'&page='.$item.'"><img src="'.$config['image_url'].'village/'.$item.'.png" id="'.$item.'" title="'.$building->de[$item].' '.$village[$item].'"></a>';
		}
	}
	echo "</div>";
} else {
	echo '<table width="300px" class="vis"><tr><th>Gebäude</th><th><b><i>'.$village['name'].'</i></b></th></tr>';
		foreach($building->name as $item => $key) {
			if($village[$item] > 0) { ?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=<? echo $item; ?>"><? echo $building->de[$item]."</td><td>".$village[$item]; ?></a></td></tr>
<?
			}
		}
		echo "</table>";
	}
?></td><td width="30px" style="vertical-align:top;">
<table border='0' width="250px" class="vis">
<tr><th colspan="2">Produktion pro Stunde<br />(mit Bonus)</th></tr>
<?
//48*3600
$wood = round($village['wood'] * 2.69 * $village['wood'] * $config['speed'] * ($village['arbwood'] / 2 / 100 + 1));
$clay = round($village['clay'] * 2.69 * $village['clay'] * $config['speed'] * ($village['arbclay'] / 2 / 100 + 1));
$iron = round($village['iron'] * 2.69 * $village['iron'] * $config['speed'] * ($village['arbiron'] / 2 / 100 + 1));
?>
<tr><td><a href="?page=wood"><img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"> Holz</a></td><td><b><? echo $wood; ?></b></td></tr>
<tr><td><a href="?page=clay"><img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"> Lehn</a></td><td><b><? echo $clay; ?></b></td></tr>
<tr><td><a href="?page=iron"><img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"> Eisen</a></td><td><b><? echo $iron; ?></b></td></tr>
<? if($village['agreement'] != "100") { ?>
<tr><th>Zustimmung</th><td><? echo $village['agreement']; ?></td></tr>
<? } ?>
<? if($village['arbmiliz'] > "0") { ?>
<tr><th>Verteidigungsbonus</th><td><?= $village['arbmiliz'] / 4 ?>%</td></tr>
<? } ?>
<tr><th colspan="2">Einheiten</th></tr>
<? foreach($units->name as $item => $key) {
if($village[$item] > 0) { ?><tr><td><b><img src="<?= $config['image_url'] ?>unit/<? echo $item; ?>.png"> <?= substr($units->de[$item], 0, 4) ?></b></td><td><b><? echo $village[$item]."</b></td></tr>"; } } ?>
</table></td></tr>
<tr><td><a href="game.php?village=<?= $hid ?>&page=overview&style=<? echo $user['village_style']; ?>">Dorfübersicht ändern</a></td><td>Gruppe:
<a href="game.php?village=<?= $hid ?>&page=groups">
<?
if(empty($village['group'])) {
	echo "keine Gruppe gewählt!";
} else {
	$groupname = $db->query("SELECT `name` FROM `group` WHERE id = '".$village['group']."'");
	echo $groupname;
} ?></a>
</td></tr>
</table>
<form method="post" action="#">
Dorfname:<input type="text" size="12" name="village_name" value="<?php echo $village['name']; ?>"><input type="submit" value="ändern" name="haven_change"></form>
