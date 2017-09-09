<?
//Suche ins get umwandeln
if(isset($_POST['search'])) {
  $return_error = "game.php?village=".$hid."&page=market&show=other&buy=".$_POST['buy']."&sell=".$_POST['sell'];
}
?>
<b>Filter:</b>
<form method="post" action="#">
Kaufe: <select name="buy">
<option value="all" selected="selected">alles</option>
<option value="wood">Holz</option>
<option value="clay">Lehm</option>
<option value="iron">Eisen</option>
</select>
Biete: <select name="sell">
<option value="all" selected="selected">alles</option>
<option value="wood">Holz</option>
<option value="clay">Lehm</option>
<option value="iron">Eisen</option>
</select>
<input type="submit" name="search" value="suchen">
</form>

<table class="vis"><tr><th>Biete</th><th>Kaufe</th><th>Anzahl</th><th>Kaufen</th></tr>
<?
$sql = "SELECT * FROM market_angebot WHERE from_village != '$hid'";
if(isset($_GET['buy']) and $_GET['buy'] != "all") {
  $sql .= " AND sell_w = '".$_GET['buy']."'";
}
if(isset($_GET['sell']) and $_GET['sell'] != "all") {
  $sql .= " AND buy_w = '".$_GET['sell']."'";
}
$result = $db->fetch($sql);
while ($angebot = $result->fetch_array()) {
  if(isset($_POST[$angebot['id']])) {
  //Kann käufer überhaupt kaufen?
  $handler_k = ceil(($angebot['buy']*$_POST['times']) / 1000);
  if($angebot['buy_w'] == "wood") { $wood_k = $angebot['buy']*$_POST['times']; } else { $wood_k = 0; }
  if($angebot['buy_w'] == "clay") { $clay_k = $angebot['buy']*$_POST['times']; } else { $clay_k = 0; }
  if($angebot['buy_w'] == "iron") { $iron_k = $angebot['buy']*$_POST['times']; } else { $iron_k = 0; }
  if($_POST['times'] <= 0 || $_POST['times'] > $angebot['anzahl']) {
    $error = "Unerlaubte Anzahl an angenommenen Angeboten";
  } elseif($wood_k > $village['wood_r'] || $clay_k > $village['clay_r'] || $iron_k > $village['iron_r']) {
    $error = "Nicht genügend Rohstoffe im Dorf!"; }
  elseif($handler_k > $village['handler']) {
    $error = "Nicht genügend Händler im Dorf!"; }
  else {
    $village_vom = $db->assoc("SELECT x,y FROM village WHERE id = '".$angebot['from_village']."'");
    $felder = sqrt(bcpow(($village_vom['x'] - $village['x']),2) + bcpow(($village_vom['y'] - $village['y']),2));
    $travel_time = round(($felder * 850) / $config['speed']);
    $end_time = $travel_time + $zeit;
    //Verkäufer wegsenden
    $handler_b = ceil(($angebot['sell']*$_POST['times']) / 1000);
    if($angebot['sell_w'] == "wood") { $wood_b = $angebot['sell']*$_POST['times']; } else { $wood_b = 0; }
    if($angebot['sell_w'] == "clay") { $clay_b = $angebot['sell']*$_POST['times']; } else { $clay_b = 0; }
    if($angebot['sell_w'] == "iron") { $iron_b = $angebot['sell']*$_POST['times']; } else { $iron_b = 0; }
    $db->no_query("INSERT INTO `market`(`handler`, `wood`, `clay`, `iron`, `gold`, `from_village`, `to_village`, `type`, `end_time`, `move_time`) VALUES ('$handler_b', '$wood_b', '$clay_b', '$iron_b', '0', '".$angebot['from_village']."', '$hid', 'send', '$end_time', '$travel_time')");
    if($_POST['times'] == $angebot['anzahl']) {
      $db->no_query("DELETE FROM market_angebot WHERE id = '".$angebot['id']."'");
    } else {
      $db->no_query("UPDATE market_angebot SET anzahl = anzahl - '".$_POST['times']."' WHERE id = '".$angebot['id']."'");
    }
    //Käufer wegsenden
    $db->no_query("INSERT INTO `market`(`handler`, `wood`, `clay`, `iron`, `gold`, `from_village`, `to_village`, `type`, `end_time`, `move_time`) VALUES ('$handler_k', '$wood_k', '$clay_k', '$iron_k', '0', '$hid', '".$angebot['from_village']."', 'send', '$end_time', '$travel_time')");
    $error = "Händler sind mit den Rohstoffen unterwegs!";
  }
}  //Angebot annehmen ENDE
echo "<form method='post' action='#'><tr><td>".$angebot['buy']."<img src='".$config['image_url']."/res/".$angebot['buy_w'].".png' width='25px'></td><td>".$angebot['sell']."<img src='".$config['image_url']."/res/".$angebot['sell_w'].".png' width='25px'></td><td>".$angebot['anzahl']."</td><td><input type='text' name='times' value='1' size='2'><input type='submit' name='".$angebot['id']."' value='annehmen'><td></tr></form>";
}
?></table>
