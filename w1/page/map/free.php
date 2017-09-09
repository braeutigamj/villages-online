<table class="vis">
<tr><th></th><th>Dorfname</th><th>Koordinaten</th><th>Enfernung</th></tr>
<?
$result = $db->fetch("SELECT id,name,x,y,(sqrt((x - ".$village['x'].") * (x - ".$village['x'].") + (y - ".$village['y'].") * (y - ".$village['y']."))) AS distance FROM village WHERE userid = '-1' ORDER BY distance ASC");
$i = 0;
while ($freevil = $result->fetch_array()) {
$i++;
$felder = round(sqrt(bcpow(($freevil['x'] - $village['x']),2) + bcpow(($freevil['y'] - $village['y']),2)));
?>
<tr><td><? echo $i; ?></td><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $freevil['id']; ?>"><? echo $freevil['name']; ?></a></td>
<td><a href="game.php?village=<?= $hid ?>&page=map&x=<? echo $freevil['x']; ?>&y=<? echo $freevil['y']; ?>"><? echo $freevil['x']; ?>|<? echo $freevil['y']; ?></a></td><td><? echo $felder; ?></td></tr>
<? } ?>
</table>
