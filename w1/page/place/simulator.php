<?
if(isset($_POST['simulate'])) {
  //Truppen vorbereiten um in den Kampf zuschicken
  $att_unit = "";
  $deff_unit = "";
  foreach($units->name as $item => $key) {
    if(empty($_POST['att_'.$item])) { $_POST['att_'.$item] = "0"; }
    $att_unit .= $_POST['att_'.$item].",";
    if(empty($_POST['att_tech_'.$item])) { $_POST['att_tech_'.$item] = "1"; }
    $other['tech']['att'][$item] = $_POST['att_tech_'.$item];
    if(empty($_POST['deff_'.$item])) { $_POST['deff_'.$item] = "0"; }
    $deff_unit .= $_POST['deff_'.$item].",";
    if(empty($_POST['def_tech_'.$item])) { $_POST['def_tech_'.$item] = "1"; }
    $other['tech']['def'][$item] = $_POST['def_tech_'.$item];
  }
  $other['wall'] = $_POST['wall'];
  $other['miliz'] = $_POST['miliz'];
  $fight = make_fight($att_unit, $deff_unit, $other);	//<- Kampf
  $ramme = $fight['att']['ram'] - $fight['att_lose']['ram'];
  $wall_steps = round($ramme / 4);
  $end_wall = $other['wall'] - $wall_steps;
  if($end_wall < 0) { $end_wall = 0; }

  $kata = $fight['att']['catapult'] - $fight['att_lose']['catapult'];
  $kata_steps = round($kata / 6);
  $end_kata = $_POST['building'] - $kata_steps;
  if($end_kata < 0) { $end_kata = 0; }
  //Format: $fight['att']['spear']
  ?>
  <table class="vis" border="0"><tr><th colspan="2"></th>
  <? foreach($units->name as $item => $key) {
  echo "<th><img src='".$config['image_url']."unit/".$item.".png'></th>";
  }
  echo '</tr><tr><td rowspan="2">Angreifer</td><td>Einheiten:</td>';
  foreach($units->name as $item => $key) {
  echo "<td>".$fight['att'][$item]."</td>";
  }
  echo "</tr><tr><td>Versluste:</td>";
  foreach($units->name as $item => $key) {
  echo "<td>".$fight['att_lose'][$item]."</td>";
  }
  echo "</tr><tr><td rowspan='2'>Verteidiger</td><td>Einheiten</td>";
  foreach($units->name as $item => $key) {
  echo "<td>".$fight['def'][$item]."</td>";
  }
  echo "</tr><tr><td>Versluste:</td>";
  foreach($units->name as $item => $key) {
  echo "<td>".$fight['def_lose'][$item]."</td>";
  } ?>
  </tr>
  </table>
  <? if($other['wall'] != $end_wall) { ?>
  Wall wurde von Stufe <b><? echo $other['wall']; ?></b> auf Stufe <b><? echo $end_wall; ?></b> reduziert!
  <?
  } if($end_kata != $_POST['building']) {
  echo "Zielgebäude der Katapulte wurde von Stufe <b>".$_POST['building']."</b> auf Stufe <b>".$end_kata."</b> reduziert";
  }
}
?>
<form method="post" action="#">
<table class="vis"><tr><th>Einheiten</th><th>Angreifer</th><th>Angreifer-Tech</th><th>Verteidiger</th><th>Verteidiger-Tech</th></tr>
<? foreach($units->name as $item => $key) {
?>
<tr><td><img src='<?= $config['image_url'] ?>unit/<? echo $item; ?>.png'></td><td><input type='text' size='1' name='att_<? echo $item; ?>'></td><td><input type='text' size='1' value='1' name='att_tech_<? echo $item; ?>'></td><td><input type='text' size='1' name='deff_<? echo $item; ?>'></td><td><input type='text' size='1' value='1' name='def_tech_<? echo $item; ?>'></td>
<? } ?>
<tr><td colspan="3">Wall-Stufe des Verteidigers:</td><td colspan="2"><input type="text" name="wall" size="1" value="0"></td></tr>
<tr><td colspan="3">Stufe Katapultziel:</td><td colspan="2"><input type="text" name="building" size="1" value="0"></td></tr>
<tr><td colspan="3">Verteidigungsbonus: (maximal 45%)</td><td colspan="2"><input type="text" name="miliz" size="1" value="0">%</td></tr>
<tr><td colspna="5"><input type="submit" name="simulate" value="Simulieren"></td></tr>
</form>
</table>
