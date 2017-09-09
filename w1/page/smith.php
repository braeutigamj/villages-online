<? if($village['smith'] > 0) { ?>
<h2>Schmiede (Stufe <? echo $village['smith']; ?>)</h2>
<p>Hier kannst du Einheiten erforschen. Die maximale Forschungsstufe ist <b>5</b>!<br />
Ab Stufe 1 kannst du die Einheiten rekrutieren. Je höher die Forschung desto stärker sind die Einheiten!</p>
<p>Preis pro Forschung: <img src="<?= $config['image_url'] ?>res/wood.png" width="25px"><b>1000</b> <img src="<?= $config['image_url'] ?>res/clay.png" width="25px"><b>1000</b>
<? $update = round(12000 / (0.3 * $village['smith']) / $config['speed']); ?>
<img src="<?= $config['image_url'] ?>res/iron.png" width="25px"><b>100</b></p>
<p>Schmiedzeit: <b><? echo format_time($update); ?></b></p>
<?
$forschungaktiv = false;
$smith = $db->assoc("SELECT * FROM `smith` WHERE villageid = '$hid' LIMIT 1");
if($smith['id'] > 0) {
$forschungaktiv = true;
$loadtime[] = $smith['end_time'];
?>
Aktuelle Forschung: <b><? echo $units->de[$smith['unit']]; ?></b><br />
Fertigstellung: <b id="<?= $smith['end_time'] ?>"></b>
<? } ?>
<form method="post" action="#">
<table class="vis"><tr>
<?
if(!$forschungaktiv) {
  echo "<th>Einheit:</th>";
  foreach($units->name as $item => $key) {
    if(isset($_POST['s'.$item]) and $village['s'.$item] < 5) {
      if(1000 < $village['wood_r'] and 1000 < $village['clay_r'] and 1000 < $village['iron_r']) {
        $update = $update + $zeit;
        $wirdgeforscht = $db->query("SELECT id FROM `smith` WHERE `villageid` = '$hid'");
        if($wirdgeforscht > 0 || $village['s'.$item] >= 5) {
          $no_error = "Es wird bereits geforscht, oder Einheit vollständig erforscht!";
        } else {
          $db->no_query("UPDATE village SET wood_r = wood_r-'1000', iron_r = iron_r-'1000', clay_r = clay_r - '1000' WHERE id = '$hid'");
          $db->no_query("INSERT INTO `smith`(`villageid`, `unit`, `end_time`) VALUES ('$hid', '$item', '$update')");
          $no_error = "Wird erforscht!";
        }
      } else {
        $error= "Nicht genügend Rohstoffe vorhanden!";
      }
    }
    if($village['s'.$item] < 5) {
      echo "<th><img src='".$config['image_url']."unit/".$item.".png' alt='".$units->de[$item]."'></th>";
    }
  }
  echo "</tr><tr><th>Aktuelle Stufe:</th>";
  foreach($units->name as $item => $key) {
    if($village['s'.$item] < 5) {
      echo "<td>".$village['s'.$item]."</td>";
    }
  }
  echo "</tr><tr><th>Erforschen:</th>";
  foreach($units->name as $item => $key) {
    if($village['s'.$item] < 5) {
      echo "<td><input type='submit' name='s".$item."' value='OK'></td>";
    }
  }
}
?></tr></table></form><hr />
<b>Hier kannst du Einheiten rekrutieren:</b><br />
<a href="?page=barracks">Barracke</a> - <a href="?page=stable">Stall</a> - <a href="?page=garage">Werkstatt</a> - <a href="?page=settlerplace">Siedlerstätte</a>
<? } else { echo "<h2>Gebäude wurde noch nicht gebaut!</h2>"; } ?>
