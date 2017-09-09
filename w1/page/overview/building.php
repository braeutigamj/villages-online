<table class="vis"><tr><th>Dorf</th><?
foreach($building->name as $item => $key) {
echo "<th width='50px'>".substr($building->de[$item], 0, 4)."</th>";
 } ?></tr>
<?
$result = $db->fetch("SELECT * FROM village WHERE userid = '$id' ORDER BY name");
while ($villages = $result->fetch_array()) { ?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=overview&new_vil=<? echo $villages['id']; ?>"><? echo $villages['name']." (".$villages['x']."|".$villages['y']; ?>)</a></td><? foreach($building->name as $item => $key) { ?>
<td><? echo $village[$item]; ?></td>
<? } ?>
</tr>
<? }
?></table>
