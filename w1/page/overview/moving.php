<table class="vis" id="center">
<tr><th>Von Dorf</th><th>Nach Dorf</th><th>Art</th><th>Ankunft</th></tr>
<?
$result = $db->fetch("SELECT type, from_village, to_village, end_time FROM `movement` WHERE from_village = '$hid' or to_village = '$hid' ORDER BY end_time");
while($mov = $result->fetch_array()) {
	echo "<tr><td><a href='game.php?village=".$hid."&page=smap&id=".$mov['from_village']."'>".$db->query("SELECT `name` FROM `village` WHERE id = '".$mov['from_village']."'")."</a></td><td><a href='game.php?village=".$hid."&page=smap&id=".$mov['to_village']."'>".$db->query("SELECT `name` FROM `village` WHERE id = '".$mov['to_village']."'")."</a></td><td><img src='".$config['image_url']."fight/".$mov['type'].".png' /></td><td id='".$mov['end_time']."'></td></tr>";
}
?>
</table>
