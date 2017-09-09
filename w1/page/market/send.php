<?
if(isset($_POST['send'])) {
  $ges_res = $_POST['wood']+$_POST['clay']+$_POST['iron']+$_POST['gold']*100;
  $to_village = $db->query("SELECT id FROM village WHERE x='".$_POST['x']."' AND y='".$_POST['y']."'");
  if($ges_res > ($village['handler'] * 1000)) {
    $error = "Nicht genügend Händler im Dorf!";
  } elseif($to_village <= 0) {
    $error = "Zieldorf konnte nicht gefunden werden!";
  } elseif($_POST['wood'] > $village['wood_r'] || $_POST['clay'] > $village['clay_r'] || $_POST['iron'] > $village['iron_r'] || $_POST['gold'] > $village['gold']) {
    $error = "Du hast nicht genügend Rohstoffe!";
  } else {
    $handler = ceil($ges_res / 1000);
    $db->no_query("UPDATE village SET wood_r = wood_r - '".$_POST['wood']."', clay_r = clay_r - '".$_POST['clay']."', iron_r = iron_r - '".$_POST['iron']."', gold = gold - '".$_POST['gold']."' WHERE id = '$hid'");
    $felder = sqrt(bcpow(($_REQUEST['x'] - $village['x']),2) + bcpow(($_REQUEST['y'] - $village['y']),2));
    $travel_time = round(($felder * 850) / $config['speed']);
    $end_time = $travel_time + $zeit;
    $db->no_query("INSERT INTO `market`(`handler`, `wood`, `clay`, `iron`, `gold`, `from_village`, `to_village`, `type`, `end_time`, `move_time`) VALUES ('$handler', '".$_POST['wood']."', '".$_POST['clay']."', '".$_POST['iron']."', '".$_POST['gold']."', '$hid', '$to_village', 'send', '$end_time', '$travel_time')");
  }
}
?>
<b>Rohstoffe auswählen:</b>
<table class="vis"><tr><td>
<form method="post" action="#">
<table>
<tr><td><img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"></td><td><input type="text" name="wood" value="0" size="3"></td></tr>
<tr><td><img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"></td><td><input type="text" name="clay" value="0" size="3"></td></tr>
<tr><td><img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"></td><td><input type="text" name="iron" value="0" size="3"></td></tr>
<tr><td><img src="<? echo $config['image_url']; ?>res/gold.png" width="25px"></td><td><input type="text" name="gold" value="0" size="2"></td></tr>
<tr><td><input type="text" name="x" value="<? if(isset($_REQUEST['x'])) { echo $_REQUEST['x']; } else { echo "XXX"; } ?>" onFocus="if(this.value=='XXX') this.value=''" size="2"></td><td><input type="text" name="y" value="<? if(isset($_REQUEST['x'])) { echo $_REQUEST['y']; } else { echo "YYY"; } ?>" onFocus="if(this.value=='YYY') this.value=''" size="2"></td></tr>
<tr><td colspan="2"><input type="submit" name="send" value="verschicken"></td></tr>
</table>
</form>
</td><td>
<?
if($db->query("SELECT count(id) FROM market WHERE to_village='$hid'") > 0) {
  $result = $db->fetch("SELECT * FROM market WHERE to_village='$hid'");
  echo "<h4>Ankommende Transporte:</h4>";
  echo "<table><tr><th>Von Dorf</th><th>Ankunft</th><th>Rohstoffe</th><th>Händler</th></tr>";
  while ($ank = $result->fetch_array()) {
    if($ank['type'] == "return" and $ank['from_village'] == $hid) {
      $berechnevon = $ank['to_village']; } else { $berechnevon = $ank['from_village'];
    }
    $fromv = $db->assoc("SELECT id,name,x,y FROM village WHERE id = '$berechnevon'");
    if(($ank['type'] == "return" and $ank['from_village'] == $hid) || ($ank['type'] == "send" and $ank['to_village'] == $hid)) {
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $fromv['id']; ?>">
<? echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td><? echo format_time($ank['end_time'] - $zeit); ?></td><td>
<? if($ank['type'] != "return") { ?>
<img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><? echo $ank['wood']; ?><img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"><? echo $ank['clay']; ?><img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"><? echo $ank['iron']; ?><img src="<? echo $config['image_url']; ?>res/gold.png" width="25px"><? echo $ank['gold']; ?><? } ?>
</td><td><? echo $ank['handler']; ?></td></tr>
<?    }
  }
  echo "</table>";
}
if($db->query("SELECT count(id) FROM market WHERE from_village='$hid'") > 0) {
  $result = $db->fetch("SELECT * FROM market WHERE from_village='$hid'");
  echo "<h4>Abgehende Transporte:</h4>";
  echo "<table><tr><th>Nach Dorf</th><th>Ankunft</th><th>Rohstoffe</th><th>Händler</th></tr>";
  while ($ank = $result->fetch_array()) {
    if($ank['type'] == "send" and $ank['from_village'] == $hid) {
      $berechnevon = $ank['to_village']; } else { $berechnevon = $ank['from_village']; }
      $fromv = $db->assoc("SELECT id,name,x,y FROM village WHERE id = '$berechnevon'");
      if(($ank['type'] == "send" and $ank['from_village'] == $hid) || ($ank['type'] == "return" and $ank['to_village'] == $hid)) {
  $loadtime[] = $ank['end_time'];
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $fromv['id']; ?>">
<? echo $fromv['name']." (".$fromv['x']."|".$fromv['y']; ?>)</a></td><td id="<?= $ank['end_time'] ?>"></td><td>
<? if($ank['type'] != "return") { ?>
<img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><? echo $ank['wood']; ?><img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"><? echo $ank['clay']; ?><img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"><? echo $ank['iron']; ?><img src="<? echo $config['image_url']; ?>res/gold.png" width="25px"><? echo $ank['gold']; ?>
      <? } ?>
</td><td><? echo $ank['handler']; ?></td></tr>
<?    }
  }
  echo "</table>";
}
?>
</td></tr></table>
