<h4>Einheiten in diesem Dorf zu Gast: </h4>
<?
if(isset($_GET['back']) and is_numeric($_GET['back'])) {
  $back = $db->assoc("SELECT * FROM support WHERE id = '".$_GET['back']."'");
  if($back['to_village'] != $hid and $back['from_village'] != $hid) {
    $error = "Keine Berechtigung!";
  } else {
    if($back['to_village'] == $hid) {
      $from = $hid;
      $zielvil = $back['from_village'];
      $ziel = $db->assoc("SELECT * FROM village WHERE id = '".$back['from_village']."'");
    } elseif($back['from_village'] == $hid and $back['to_village'] == 0) {
      $from = $back['to_mountain'];
      $zielvil = $hid;
      $ziel = $db->assoc("SELECT * FROM mountain WHERE id = '".$back['to_mountain']."'");
    } else {
      $zielvil = $hid;
      $from = $back['to_village'];
      $ziel = $db->assoc("SELECT * FROM village WHERE id = '".$back['to_village']."'");
    }
    $time_per_feld = 0;
    $unit = explode(",", $back['units']);
    foreach($units->name as $item => $key) {
      if($time_per_feld < $units->move_time[$item]) {
        $time_per_feld = $units->move_time[$item];
      }
    }
    $felder = sqrt(bcpow(($ziel['x'] - $village['x']),2) + bcpow(($ziel['y'] - $village['y']),2));
    $travel_time = round(($felder * $time_per_feld) / $config['speed']);
    $end_time = $travel_time + $zeit;
    $db->no_query("INSERT INTO `movement`(`type`, `from_village`, `to_village`, `start_time`, `end_time`, `units`) VALUES ('return', '$from', '$zielvil', '$zeit', '$end_time', '".$back['units']."')");
    $db->no_query("DELETE FROM support WHERE id = '".$back['id']."'");
    $error = "Truppenunterstützung wurde beendet!";
  }
}
$result = $db->fetch("SELECT * FROM support WHERE to_village = '$hid'");
 ?>
<table class="vis"><tr><th>Heimatdorf</th><? foreach($units->name as $item => $key) {
echo "<th><img src='".$config['image_url']."unit/".$item.".png'></th>";
 } ?><th colspan="2"></th></tr><? while ($support = $result->fetch_array()) {
$supportu = explode(",", $support['units']);
$vname = $db->query("SELECT name FROM village WHERE id = '".$support['from_village']."'");
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $support['from_village']; ?>"><? echo $vname; ?></a></td>
<? for($i = 0; $i <= 11; $i++) {
echo "<td>".$supportu[$i]."</td>";
}
echo "<td><a href='game.php?village=<?= $hid ?>&page=place&show=befehl&back=".$support['id']."'>zurückschicken</a></td></tr>";
} ?>
</table>
<h4>Einheiten von diesem Dorf:</h4>
<?
$result = $db->fetch("SELECT * FROM support WHERE from_village = '$hid' AND to_village > '0'");
 ?>
<table class="vis"><tr><th>Zieldorf</th><? foreach($units->name as $item => $key) {
echo "<th><img src='".$config['image_url']."unit/".$item.".png'></th>";
 } ?><th colspan="2"></th></tr><? while ($support = $result->fetch_array()) {
$supportu = explode(",", $support['units']);
$vname = $db->query("SELECT name FROM village WHERE id = '".$support['to_village']."'");
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $support['to_village']; ?>"><? echo $vname; ?></a></td>
<? for($i = 0; $i <= 11; $i++) {
echo "<td>".$supportu[$i]."</td>";
}
echo "<td><a href='game.php?village=<?= $hid ?>&page=place&show=befehl&back=".$support['id']."'>zurückschicken</a></td></tr>";
} ?>
</table>
