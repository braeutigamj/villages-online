<?
if($village['main'] >= 11 and $village['agreement'] == 100) {
  if(isset($_GET['destroy'])) {
    if($_GET['destroy'] < 1) {
      $_GET['destroy'] = 1;
    }
    $buildingname = $building->get_name($_GET['destroy']);
    $wood = round($building->wood[$buildingname]*0.01*($village[$buildingname]+1));
    $iron = round($building->iron[$buildingname]*0.01*($village[$buildingname]+1));
    $clay = round($building->clay[$buildingname]*0.01*($village[$buildingname]+1));
    $food = round($building->food[$buildingname]*1/2*($village[$buildingname]+1));
    if ($wood > $village['wood_r'] || $iron > $village['iron_r'] || $clay > $village['clay_r'] || ($village[$buildingname] - 1) < 0) {
      $error = "Abbaukosten zu hoch!";
    } else {
      $pointss = round($building->points[$buildingname] * 0.5 * $village[$buildingname]) + 1;
      $sql = "UPDATE village SET wood_r = wood_r-'$wood', iron_r = iron_r-'$iron', clay_r = clay_r - '$clay', food=food-'$food', points=points-'".$pointss."', $buildingname = $buildingname - '1'";
      if($buildingname == "wood" || $buildingname == "clay" || $buildingname == "iron") {
        if($village['arb'.$buildingname] >= $config['worker']) {
          $sql .= ", arb".$buildingname." = arb".$buildingname." - ".$config['worker'];
        } else {
          $restworker = $config['worker'] - $village['arb'.$buildingname];
          if($village['arbnone'] >= $restworker) {
            $sql .= ", arb".$buildingname." = arb".$buildingname." - ".$config['worker'].", arbnone - ".$restworker;
          } else {
            $return_error = "game.php?village=".$hid."&page=main&show=worker&do=reset";
          }
        }
      }
      $sql .= " WHERE id = '$hid'";
      $db->no_query($sql);
      $db->no_query("UPDATE user SET points = points-'".$pointss."' WHERE id = '$id'");
      $no_error = true;
    }
  }
?>
<table class="vis"><tr><th>Gebäude</th><th>Stufe</th><th>Abbaukosten</th><th>Abbauen</th></tr>
<?
  foreach($building->name as $item => $key) {
    if($village[$building->name[$item]] >= 1 and $item != "farm" and ($item != "storage" || $village[$item] > 1)) {
      $wood = round($building->wood[$item]*0.01*($village[$item]+1));
      $iron = round($building->iron[$item]*0.01*($village[$item]+1));
      $clay = round($building->clay[$item]*0.01*($village[$item]+1));
      $food = round($building->food[$item]*1/2*($village[$item]+1));
      $c1 = ceil(($village[$building->name[$item]]*100)/$building->level_max[$item]);
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=<? echo $building->name[$item]; ?>"><? echo $building->de[$item]; ?></a></td><td><b><? echo $village[$building->name[$item]]; ?></b></td><td><b><img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><? echo $wood."</b> <img src='".$config['image_url']."res/clay.png' width='25px'><b>".$clay."</b>
<img src='".$config['image_url']."res/iron.png' width='25px'><b>".$iron."</b> <img src='".$config['image_url']."res/food.png' width='25px'><b>".$food; ?></b></td><td width="100" align="center"><?
      if ($wood > $village['wood_r'] || $iron > $village['iron_r'] || $clay > $village['clay_r']) {
        echo "Nicht genügend Rohstoffe vorhanden!";
      }
      else { ?>
              <div class="progress"><div class="progress data" style="width:<? echo $c1; ?>%;" title="<? echo $c1; ?>%">&nbsp;</div></div>
            </td>
            <td align="center">
<a href="game.php?village=<?= $hid ?>&page=main&amp;show=destroy&destroy=<? echo $building->id[$item]; ?>"><img src="<?= $config['cdn'] ?>img/minus.png"></a>

<?      }
?></td>

<?
    }
  } ?>
</table>
<?
}
else {
  echo "Du hast keine Berechtigung diese Seite zu sehen! Zurück zum <a href='game.php?village=".$hid."&page=main'> Hauptgebäude</a>!";
}
?>
