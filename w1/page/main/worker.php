<?
if(isset($_GET['do']) and $_GET['do'] == "reset") {
  $reload->arbeiter($hid);
  $no_error = true;
}
if(isset($_POST['abberufen'])) {
  $number = round($_POST['number']);
  if($village['arb'.$_POST['type']] >= $number) {
      $db->no_query("UPDATE `village` SET arb".$_POST['type']." = arb".$_POST['type']." - $number, arbnone = arbnone + $number WHERE id = '$hid'");
      $no_error = true;
  } else {
    $error = "Du kannst maximal so viel abberufen, wie auch dort arbeiten!";
  }
}
if(isset($_POST['berufen'])) {
  $number = round($_POST['number']);
  if($village['arbnone'] >= $number) {
      $db->no_query("UPDATE `village` SET arb".$_POST['type']." = arb".$_POST['type']." + $number, arbnone = arbnone - $number WHERE id = '$hid'");
      $no_error = true;
  } else {
    $error = "Du kannst maximal so viel berufen, wie du unverteilte Arbeiter hast!";
  }
}
?>
<p>Hier kannst du deine Arbeiter neu verteilen um z.B. verstärkt andere Ressourcen zu produzieren oder Milizen zu berufen (erhöht Verteidungswert). Pro Ausbaustufe der Gebäude Holzfäller, Lehmgrube und Eisenmine bekommst du <?= $config['worker'] ?> Arbeiter dazu! Du kannst auch deine Verteilung <a href="game.php?village=<?= $hid ?>&page=main&show=worker&do=reset">zurücksetzen</a>!</p>
<table class="vis"><tr><th colspan="2">aktuelle Verteilung</th><th>Steigerung</th></tr>
<tr><td>Arbeiter insgesammt</td><td><?= $village['arbwood'] + $village['arbclay'] + $village['arbiron'] + $village['arbmiliz'] + $village['arbnone'] ?></td><td>---</td></tr>
<tr><td>Arbeiter Holzfäller</td><td><?= $village['arbwood'] ?></td><td><?= $village['arbwood'] / 2 ?>%</td></tr>
<tr><td>Arbeiter Lehmgrube</td><td><?= $village['arbclay'] ?></td><td><?= $village['arbclay'] / 2 ?>%</td></tr>
<tr><td>Arbeiter Eisenmine</td><td><?= $village['arbiron'] ?></td><td><?= $village['arbiron'] / 2 ?>%</td></tr>
<tr><td>Arbeiter als Miliz berufen</td><td><?= $village['arbmiliz'] ?></td><td><?= $village['arbmiliz'] / 4 ?>%</td></tr>
<tr><td>unverteilte Arbeiter</td><td><?= $village['arbnone'] ?></td><td>---</td></tr>
</table>
<br />
<form method="post" action="#">
<input type="text" name="number" size="1"> Arbeiter von <select name="type">
  <option value="wood">Holzfäller</option>
  <option value="clay">Lehmgrube</option>
  <option value="iron">Eisenmine</option>
  <option value="miliz">Milizen</option>
</select> abberufen. <input type="submit" name="abberufen" value=">>OK<<">
</form>
<br />
<form method="post" action="#">
<input type="text" name="number" size="1"> Arbeiter zu <select name="type">
  <option value="wood">Holzfäller</option>
  <option value="clay">Lehmgrube</option>
  <option value="iron">Eisenmine</option>
  <option value="miliz">Milizen</option>
</select> berufen. <input type="submit" name="berufen" value=">>OK<<">
</form>
