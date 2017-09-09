<table class="vis"><tr><th>Dorf</th><?
foreach($building->name as $item => $key) {
echo "<th><img src='".$config['image_url']."v_small/".$item.".png'></th>";
 } foreach($units->name as $item => $key) {
echo "<th><img src='".$config['image_url']."unit/".$item.".png'></th>";
 } ?></tr>
<?
$result = $db->fetch("SELECT * FROM village WHERE userid = '$id' AND `group` = '".$_GET['id']."' ORDER BY name");
while ($villages = $result->fetch_array()) { ?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=overview&new_vil=<? echo $villages['id']; ?>"><? echo $villages['name']." (".$villages['x']."|".$villages['y']; ?>)</a></td><? foreach($building->name as $item => $key) { ?>
<td><? echo $villages[$item]; ?></td>
<? } foreach($units->name as $item => $key) { ?>
<td><? echo $villages[$item]; ?></td>
<? } ?>
</tr>
<? }
?></table>
