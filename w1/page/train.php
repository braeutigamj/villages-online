<? if ($village[$_REQUEST['page']] > 0) { ?>
<h2><? if($_REQUEST['page'] == "stable") { echo "Stall"; } elseif($_REQUEST['page'] == "garage") { echo "Werkstatt"; } elseif($_REQUEST['page'] == "settlerplace") { echo "Siedlerstätte"; } else { echo "Barracke"; } ?> (Stufe <? echo $village[$_REQUEST['page']]; ?>)</h2>
<p>In der <? if($_REQUEST['page'] == "stable") { echo "Stall"; } elseif($_REQUEST['page'] == "garage") { echo "Werkstatt"; } elseif($_REQUEST['page'] == "settlerplace") { echo "Siedlerstätte"; } else { echo "Barracke"; } ?> kannst du Einheiten ausbilden. Diese schützen dein Dorf vor Angreifern.</p>
<? if($_REQUEST['page'] == "barracks") { $requesttype = "1"; } elseif($_REQUEST['page'] == "garage") { $requesttype="3"; } elseif($_REQUEST['page'] == "settlerplace") { $requesttype="5"; } else { $requesttype = "2"; }
if($requesttype=="5") { ?>
<p>Siedlergold im Dorf: <b><? echo $village['gold']; ?></b><img src='<? echo $config['image_url']; ?>res/gold.png' width='25px'></p>
<? }
if($db->query("SELECT * FROM train WHERE village='$hid' AND type = '$requesttype'")) { ?>
<table class="vis">
<tr><th>Einheit</th><th>Anzahl*</th><th>Dauer</th><th>Ausgebaut</th></tr>
<?
//Schauen welche Einheiten ausgebildet werden können
$result = $db->fetch("SELECT * FROM train WHERE village='$hid' AND type = '$requesttype'");
$unitsa = 0;
while ($train = $result->fetch_array()) {
$unitsd = $train['times']-$train['finished'];
if($train['what'] == "settler" || $train['what'] == "hut") { $unitsa += 1; }   //Addiere 1 falls settlerplace
echo "<tr><td>".$units->de[$train['what']]."</td><td>".$unitsd."</td>";
$loadtime[] = $train['end_time'];
echo "<td id='".$train['end_time']."'></td>";
echo "<td>".format_date($train['end_time'])."</td>";
} ?>
</table>
<h6>*Noch zu Produzieren</h6>
<? } ?>
<form method='post' action='#'>
<table class="vis">
<tr><th colspan="2">Einheit</th><th>Benötigt</th><th>Dauer<br />pro Einheit</th><th>Vorhanden</th><th>Rekrutieren</th><th>maximal</th></tr>
<?
if($requesttype == 5) {
        $result = $db->fetch("SELECT id,settler,hut FROM village WHERE userid='$id'");
        $i = 0;
        if(!isset($unitsa)) { $unitsa = 0; }
        while ($villages = $result->fetch_array()) {
          $i++;
          $unitsa += $villages['settler'];
          $unitsa += $villages['hut'];
          $resulta = $db->fetch("SELECT units FROM support WHERE from_village='".$villages['id']."'");
          while ($supports = $resulta->fetch_array()) {
            $settlerplace = explode(",", $supports['units']);  // $settlerplace[12] gleich settlerplace!
            $unitsa += $settlerplace[12];
            $unitsa += $settlerplace[13];
          }
        }
        $unitsa = $unitsa + $i;
        $gold = round($unitsa * 1.33);
}
$wache_time = ($village[$_REQUEST['page']] / 2.43);
foreach($units->name as $item => $key) {
if($units->type[$item] == $requesttype) {
  $unit_time = round(($units->time[$item] / $wache_time) / $config['speed']);
  $unit_time = $unit_time + 1;
  if($village['s'.$item] < 1) {
    echo "<tr><td colspan='7' style='text-align: left;'><img src='".$config['image_url']."unit/".$item.".png'> ".$units->de[$item]." wurde noch nicht erforscht!</td></tr>";
  } else {
    echo "<tr>
      <td><img src='".$config['image_url']."unit/".$item.".png'></td>
      <td>".$units->de[$item]."</td>
      <td>
        <img src='".$config['image_url']."res/wood.png' width='25px'>
        <b>".$units->wood[$item]."</b>
        <img src='".$config['image_url']."res/clay.png' width='25px'>
        <b>".$units->clay[$item]."</b>
        <img src='".$config['image_url']."res/iron.png' width='25px'>
        <b>".$units->iron[$item]."</b>
        <img src='".$config['image_url']."res/food.png' width='25px'>
        <b>".$units->food[$item]."</b>";
    if($requesttype == 5) {
      echo "
        <img src='".$config['image_url']."res/gold.png' width='25px'>
        <b>".$gold."</b>";
    }
    echo "</td><td>".format_time($unit_time)."</td><td>".$village[$item];
    $maxunit[0] = floor($village['wood_r'] / $units->wood[$item]);
    $maxunit[1] = floor($village['clay_r'] / $units->clay[$item]);
    $maxunit[2] = floor($village['iron_r'] / $units->iron[$item]);
    $maxunit[3] = floor(
        ($village['max_food'] - $village['food']) / $units->food[$item]);
    if($requesttype == 5) {
      $maxunit[4] = floor($village['gold'] / $gold);
    }
    echo "</td><td><input type='text' name='".$item."' size='2'></td>";
    if($requesttype == 5 and min($maxunit) > 1) {
      echo "<td>1</td></tr>";
    } else {
      echo "<td>".min($maxunit)."</td></tr>";
    }
    if(isset($_POST['train'])) {
      $_POST[$item] = round($_POST[$item]);
       //$item Anzahl der Einheiten
      if($_POST[$item] > 0) {
        $wood[$item] = $units->wood[$item] * $_POST[$item];
        $clay[$item] = $units->clay[$item] * $_POST[$item];
        $iron[$item] = $units->iron[$item] * $_POST[$item];
        $food[$item] = $units->food[$item] * $_POST[$item];
        if($wood[$item] > $village['wood_r'] || $iron[$item] > $village['iron_r'] || $clay[$item] > $village['clay_r']) {
          $error = "Nicht genügend Rohstoffe!";
      } elseif($food[$item] + $village['food'] > $village['max_food']) {
          $error = "Nicht genügend Farm-Plätze!";
      } elseif($requesttype == 5 and $gold > $village['gold']) {
          $error = "Nicht genügend Gold im Dorf!";
      } elseif($_POST[$item] > 1 and $requesttype == 5) {
          $error = "Du kannst nur einen Siedler auf einmal in Auftrag geben!";
      } else {
        if($requesttype == 5) {
          $db->no_query("UPDATE village SET gold = gold-'$gold' WHERE id = '$hid'");
        }
        $db->no_query("UPDATE village SET wood_r = wood_r-'".$wood[$item]."', clay_r = clay_r-'".$clay[$item]."', iron_r = iron_r-'".$iron[$item]."', food = food+'".$food[$item]."' WHERE id = '$hid'");
        createtrain($wache_time, $item,$_POST[$item],$requesttype);
        $no_error = true;
      }
    }
  }
}
}
}
?>
<tr><td colspan="6"></td><td><input type="submit" name="train" value="rekrutieren"></td></tr>
</table>
</form>
<? if($requesttype == 5) {
  if(isset($_POST['produzieren'])) {
    if(15000*$_POST['times'] > $village['wood_r'] || 20000*$_POST['times'] > $village['iron_r'] || 16000*$_POST['times'] > $village['clay_r']) {
      $error = "Nicht genügend Rohstoffe!";
    } else {
      $wood = 15000*$_POST['times'];
      $iron = 20000*$_POST['times'];
      $clay = 16000*$_POST['times'];
      $db->no_query("
          UPDATE village
          SET wood_r = wood_r-'$wood',
            clay_r = clay_r-'$clay',
            iron_r = iron_r-'$iron'
          WHERE id = '".$village['id']."'");
      $db->no_query("UPDATE village SET gold = gold+'".$_POST['times']."' WHERE id = '$hid'");
      $no_error = "1 Siedlergold wurde produziert!";
    }
  }
?>
<h4><img src='<? echo $config['image_url']; ?>res/gold.png' width='25px'>Siedlergold produzieren:</h4>
<p>Gold kann im Marktplatz transportiert werden!<br /><a href="game.php?village=<?= $hid ?>&page=market">Marktplatz</a></p>
Preis: <img src='<?= $config["image_url"] ?>res/wood.png' width='25px'><b>15.000</b> <img src="<?= $config['image_url'] ?>res/clay.png"><b>16.000</b> <img src="<?= $config['image_url'] ?>res/iron.png"><b>20.000</b><form method="post" action="#"><input type="text" value="1" name="times" size="2"><input type="submit" value="Gold produzieren" name="produzieren"></form>
<?
}
echo "<p>Weitere Einheiten können in der <u><a href='?page=smith'>Schmiede</a></u> erforscht werden!</p>";
} else { echo "<h2>Gebäude wurde noch nicht gebaut!</h2>"; } ?>
