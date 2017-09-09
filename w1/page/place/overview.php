<?
if(!isset($_GET['x'])) {
  $_GET['x'] = "";
}
if(!isset($_GET['y'])) {
  $_GET['y'] = "";
}
if(isset($_POST['support']) || isset($_POST['attack'])) {
  if(isset($_POST['support'])) { $type = "support"; } else { $type = "attack"; }
  $to_village = $db->query("SELECT id FROM village WHERE x='".$_REQUEST['x']."' AND y='".$_REQUEST['y']."'");
  $to_user = $db->query("SELECT userid FROM village WHERE id = '$to_village'");
  if($to_village <= 0) {
    $to_village = $db->query("SELECT id FROM mountain WHERE  x='".$_REQUEST['x']."' AND y='".$_REQUEST['y']."'");
    $to_user = $db->query("SELECT userid FROM mountain WHERE id = '$to_village'");
    $type .= "_mountain";
    if($to_village <= 0) {
      echo "Dorf wurde nicht gefunden!";
      exit;
    }
  }
  if($_REQUEST['x'] == $village['x'] AND $_REQUEST['y'] == $village['y']) {
    echo "Du kannst deine Truppen nicht in das Ausgangsdorf senden!";
    exit;
  }
  $unit = "";
  $time_per_feld = 0;
  $sql = "UPDATE village SET";
  foreach($units->name as $item => $key) {
    //Gibt allen Einheiten die nicht definiert sind die Zahl 0
    if($_POST[$item] > $village[$item]) { $error = "So viele Einheiten hast du nicht!"; }
    if(empty($_POST[$item])) { $_POST[$item] = "0"; }
    if($time_per_feld < $units->move_time[$item] and $_POST[$item] != "0") {
      $time_per_feld = $units->move_time[$item];
    }
    $unit .= $_POST[$item].",";
    //Truppen löschen
    $sql .= " `".$item."` = `".$item."` - '".$_POST[$item]."', ";
  }
  //Truppen löschen
  $sql = substr($sql, 0, -2);
  $sql .= " WHERE id = '$hid'";
  $db->no_query($sql);
  $felder = sqrt(bcpow(($_REQUEST['x'] - $village['x']),2) + bcpow(($_REQUEST['y'] - $village['y']),2));
  $travel_time = round(($felder * $time_per_feld) / $config['speed']);
  $end_time = $travel_time + $zeit;
  if(!isset($error)) {
    $db->no_query("INSERT INTO `movement`(`type`, `from_village`, `to_village`, `to_user`, `start_time`, `end_time`, `units`, `booty`) VALUES ('$type', '$hid', '$to_village', '$to_user', '$zeit', '$end_time', '$unit', '".$_POST['building']."')");
  }
  $no_error = true;
}
$i = 0;
echo '<form method="post" action="#"><table class="vis"><tr><th colspan="8">Truppen versenden</th><td rowspan="6"><img src="'.$config["image_url"].'attack.png"></td><tr>';
foreach($units->name as $item => $key) {
  $i++;
  echo "<td><img src='".$config['image_url']."unit/".$item.".png' ></td><td><input type='text' size='1' name='".$item."'> <br />(<b>".$village[$item]."</b>)</td>";
  if(($i % 4) == 0) {
    echo "</tr><tr>";
  }
}
echo "<td></td><td></td></tr><tr>";
echo '<td colspan="3">Zielgebäude der Katapulte: </td><td colspan="5"><select name="building" size="1">';
foreach($building->name as $item => $key) {
echo "<option value='".$item."'>".$building->de[$item]."</option>";
}
?>
</select></td></tr>
<tr><td colspan="8"><input type="text" name="x" value="<? echo $_GET['x']; ?>" size="3"><input type="text" name="y" value="<? echo $_GET['y']; ?>" size="3"><input type="submit" name="attack" value="Angreifen"><input type="submit" name="support" value="Unterstützen"></td></tr></table>
</form>
<br />
<br />
<?  //Nun noch aktuelle Angriffe auslessen
$result = $db->fetch("SELECT * FROM movement WHERE to_village='$hid'");
if($db->query("SELECT count(id) FROM movement WHERE to_village='$hid' LIMIT 1") > 0) {
echo "<table class='vis' width='100%'><tr><th width='8%'>Ankommende Truppen</th><th>Von Dorf</th><th>Ankunft</th></tr>";
while ($ank = $result->fetch_array()) {
  $fromv = $db->assoc("SELECT name,x,y FROM village WHERE id = '".$ank['from_village']."'");
?>
<tr><td><img src="<?= $config['image_url'] ?>fight/<? echo $ank['type']; ?>.png"></td><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $ank['from_village']; ?>">
<? echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td id="<?= $ank['end_time'] ?>"><? $loadtime[] = $ank['end_time']; ?></td></tr>
<?
 }
echo "</table>"; }
$result = $db->fetch("SELECT * FROM movement WHERE from_village='$hid'");
if($db->query("SELECT count(id) FROM movement WHERE from_village='$hid' LIMIT 1") > 0) {
  echo "<table class='vis' width='100%'><tr><th width='8%'>Abgehende Truppe</th><th>Ziel Dorf</th><th>Ankunft</th></tr>";
  while ($ank = $result->fetch_array()) {
    $fromv = $db->assoc("SELECT name,x,y FROM village WHERE id = '".$ank['to_village']."'");
    if(!empty($fromv['x'])) {
?>
<tr><td><img src="<?= $config['image_url'] ?>fight/<? echo $ank['type']; ?>.png"></td><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $ank['to_village']; ?>">
<? echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td id="<?= $ank['end_time'] ?>"><? $loadtime[] = $ank['end_time']; ?></td></tr>
<?  } else {
    $fromv = $db->assoc("SELECT name,x,y FROM mountain WHERE id = '".$ank['to_village']."'");
?>
<tr><td><img src="<?= $config['image_url'] ?>fight/<? echo $ank['type']; ?>.png"></td><td><a href="game.php?village=<?= $hid ?>&page=mmap&id=<? echo $ank['to_village']; ?>">
<?    echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td id="<?= $ank['end_time'] ?>"><? $loadtime[] = $ank['end_time']; ?></td></tr>
<?   }
}
echo "</table>";
 } ?>
