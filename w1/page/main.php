<?
if(!isset($_GET['show']) || empty($_GET['show'])) { $_GET['show'] = "overview"; }
//HAUPTGEBÄUDE KOTIENT MUSS MIT EINGERECHNET WERDEN! ( Fixed)
$main_factor = round(0.3*$village['main'])+1;
if(isset($_POST['haven_change'])) {
  $db->no_query("UPDATE village SET name = '".$_POST['village_name']."' WHERE id = '".$_SESSION['hid']."'");
  $no_error = "Dein Dorf wurde in ".$_POST['village_name']." umgetauft!";
}
?>
<h2>Hauptgebäude (Stufe <? echo $village['main']; ?>)</h2>
<p>Im Hauptgebäude kannst du neue Gebäude bauen oder deine Gebäude ausbauen. Du kannst hier deinem Dorf auch einen anderen Namen geben.</p>
<div id="center"><a href="game.php?village=<?= $hid ?>&page=main&show=worker">Arbeiter</a> - <a href="game.php?village=<?= $hid ?>&page=main&show=overview">Bauen</a>
<? if($village['main'] >= 11 and $village['agreement'] == 100) { ?> - <a href="game.php?village=<?= $hid ?>&page=main&show=destroy">Zerstören</a><? } ?></div>
<? if($_GET['show'] == "overview") {
//Ausbauen GUGKEN OB GEBÄUDE GEBAUT WERDEN KANN!
if(isset($_GET['bid'])) {
$buildingname = $building->get_name($_GET['bid']);
$anzahlb = $db->query("SELECT count(*) FROM build WHERE village = '$hid' AND what = '".$buildingname."'");
$villagei[$buildingname] = $village[$buildingname] + $anzahlb;
$wood = round($building->wood[$buildingname]*($villagei[$buildingname]+1));
$clay = round($building->clay[$buildingname]*($villagei[$buildingname]+1));
$iron = round($building->iron[$buildingname]*($villagei[$buildingname]+1));
$food = round($building->food[$buildingname]*($villagei[$buildingname])*0.224);
$thebuild['time'] = round($building->time[$buildingname] * ($villagei[$buildingname]+1) * 1.644);
if($building->getneed($buildingname) != 1) {
  $error = "Gebäudevoraussetzungen nicht gegeben!";
} elseif ($wood > $village['wood_r'] || $iron > $village['iron_r'] || $clay > $village['clay_r']) {
  $error = "Nicht genügend Rohstoffe vorhanden!";
} elseif ($food+$village['food'] > $village['max_food'] and $buildingname != "farm") {
  $error = "Nicht genügend Farmplätze frei!";
} else {
  //Endzeit des letzten Gebäudes
  $end_time = $db->query("SELECT end_time from build where village='$hid' order by id desc Limit 1");
  if($end_time < $zeit) {
    $end_time = $zeit;
  }
  //Resourcen abziehen
  $db->no_query("UPDATE village SET wood_r = wood_r-'$wood', iron_r = iron_r-'$iron', clay_r = clay_r - '$clay', food=food+'$food' WHERE id = '$hid'");
  $village['wood_r'] -= $wood;
  $village['clay_r'] -= $clay;
  $village['iron_r'] -= $iron;
  $village['food'] += $food;
  //Gebäude bauen + Zeit berechnen
  $build_time = round(($thebuild['time'] / $main_factor) / $config['speed'])+1;
  $end_time = $end_time + $build_time;
  $db->no_query("INSERT into build (village,end_time,what,build_time) VALUES ('$hid','$end_time','".$buildingname."','$build_time')");
  }
}

//Bauauftrag abbrechen
if(isset($_GET['stop'])) {
  $stop = $db->assoc("SELECT * FROM build WHERE id='".$_GET['stop']."' AND village='$hid'");
  if($stop['id'] > 0) {
    $rest_time = $stop['end_time'] - $zeit;
    if($rest_time > 0) {
      //Alle restlichen Bauaufträge die zeit verkürzen
      $result = $db->fetch("SELECT * FROM build WHERE village='$hid' AND id > '".$_GET['stop']."'");
      while ($rest = $result->fetch_array()) {
        $new_time = $rest['end_time'] - $rest_time;
        $db->no_query("UPDATE build SET `end_time` = '$new_time' WHERE id = '".$rest['id']."'");
      }
      //Aktuellen Bauauftrag löschen
      $db->no_query("DELETE FROM build WHERE id='".$_GET['stop']."' AND village='$hid'");
      $reload->bh($hid);
      $no_error = true;
    }
  }
}
if($db->query("SELECT * FROM build WHERE village='$hid'")) {
  echo '<table class="vis"><tr><th>Gebäude</th><th>Dauer</th><th>Ausgebaut</th><th>Abbrechen*</th></tr>';
  $result = $db->fetch("SELECT * FROM build WHERE village='$hid' ORDER BY ID ASC");
  while ($build = $result->fetch_array()) {
    if(!isset($step[$build['what']])) {
      $step[$build['what']] = $village[$build['what']] + 1;
    } else {
      $step[$build['what']] = $step[$build['what']] +1;
    }
    echo "<tr><td><a href='?page=".$build['what']."'>".$building->de[$build['what']]." (Stufe ".$step[$build['what']].")</a></td>";
    echo '<td id="'.$build['end_time'].'"></td>';
    $loadtime[] = $build['end_time'];
        echo "<td>".format_date($build['end_time'])."</td><td><a href='game.php?village=".$hid."&page=main&show=overview&stop=".$build['id']."'>abbrechen</a></td>";
      }
    echo "</table>";
    }
?>
* Es werden keine Rohstoffe zurückerstattet
<table class="vis">
<tr><th>Gebäude</th><th width="310px">Benötigt</th><th>Bauzeit</th><th>Baustatus</th><th colspan="2">Bauen</th></tr>
<?

  foreach($building->name as $item => $key) {
    if(isset($step[$item])) {
      $village[$item] = $step[$item];
    }
    if($village[$item] < $building->level_max[$item] || $user['main_show'] == 0) {
      $wood = round($building->wood[$item]*($village[$item]+1));
      $iron = round($building->iron[$item]*($village[$item]+1));
      $clay = round($building->clay[$item]*($village[$item]+1));
      $food = round($building->food[$item]*($village[$item])*0.224);
      $building_time = round($building->time[$item] * ($village[$item]+1) * 1.644);
      $c1 = ceil(($village[$item]*100)/$building->level_max[$item]);
      $isokay = $building->getneed($item);
      if($user['build_show'] == 0 or $isokay == 1) {
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=<? echo $item; ?>"><? echo $building->de[$item]; ?> (Stufe <? echo $village[$item]; ?>)</a></td><td><b><img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><? echo $wood."</b> <img src='".$config['image_url']."res/clay.png' width='25px'><b>".$clay."</b>
<img src='".$config['image_url']."res/iron.png' width='25px'><b>".$iron."</b> <img src='".$config['image_url']."res/food.png' width='25px'><b>".$food; ?></b></td><td><? echo format_time((round(($building_time /$main_factor) / $config['speed']))+1); ?></td>
<?
        if($isokay != 1) {
          if(empty($isokay)) { echo "<td width='100' align='center' colspan='3'>Alle Vorrausetzungen erfüllt!";
          } else {
            echo "<td width='100' align='center' colspan='3'>Erforderlich:<br />";
            foreach($isokay as $item => $key) {
            echo $building->de[$item]." Stufe ".$key." ";
            }
          }
        }
        elseif ($wood > $village['wood_r'] || $iron > $village['iron_r'] || $clay > $village['clay_r']) {
          echo "<td width='100' align='center' colspan='3'>Nicht genügend Rohstoffe vorhanden!";
        }
        elseif($food+$village['food'] > $village['max_food'] and $item != "farm") {
          echo "<td width='100' align='center' colspan='3'>Nicht genügend Platz in der Farm!";
        }
        elseif($village[$item] >= $building->level_max[$item]) {
          echo "<td width='100' align='center' colspan='3'>Gebäude vollständig ausgebaut!";
        } else { ?>
              <td><div class="progress"><div class="progress data" style="width:<? echo $c1; ?>%;" title="<? echo $c1; ?>%">&nbsp;</div></div>
            </td>
            <td align="center">
<a href="game.php?village=<?= $hid ?>&page=main&amp;show=overview&bid=<? echo $building->id[$item]; ?>">Ausbau auf Stufe <i><?= $village[$item]+1 ?></i></a></td><td><a href="game.php?village=<?= $hid ?>&page=main&amp;show=overview&bid=<? echo $building->id[$item]; ?>"><img src="<?= $config['image_url'] ?>plus.png"></a>
<? } } ?>
</td></tr>

<? } }
echo "</table>";
}  //show=overview
elseif($_GET['show'] == "destroy") {
include "page/main/destroy.php"; }
elseif($_GET['show'] == "worker") {
include "page/main/worker.php";
}
?><br />Weitere Einstellungsmöglichkeiten bei den <a href="game.php?village=<?= $hid ?>&page=settings">Einstellungen</a>!
<form method="post" action="#"><table>
<tr><td>Dorfname:</td><td colspan="2"><input type="text" size="12" name="village_name" value="<?php echo $village['name']; ?>"></td><td colspan="3"><center><input type="submit" value="ändern" name="haven_change"></center></td></tr>
</table></form>
