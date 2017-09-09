<?
if($user['admin'] > 0) {
  if(isset($_POST['free_vil'])) {
    set_time_limit(0);
    for($i = 0; $i < $_POST['value']; $i++) {
      for($ifexist = 2; $ifexist != 0; ) {
        $koord = showkoord('z');
        $ifexist = $db->query("SELECT count(id) FROM `village` WHERE x = '".$koord['x']."' AND y = '".$koord['y']."'");  //Ob Bereits ein Dorf mit diesen Koordinaten
      }
      $db->no_query("INSERT INTO `village`(`userid`, `name`, `x`, `y`, `main`, `place`, `wood`, `clay`, `iron`, `farm`, `storage`, `spear`, `sword`, `axe`, `archer`) VALUES ('-1', 'Freies Dorf', '".$koord['x']."', '".$koord['y']."', '2', '1', '4', '22', '11', '7', '10', '44', '22', '11', '44')");
      $vilid = $db->query("SELECT id FROM `village` ORDER BY id DESC LIMIT 1");
      $reload->bh($vilid);
      $reload->village_points($vilid);
    }
    $no_error = true;
  }
  if(isset($_POST['reloadal'])) {
  $result = $db->fetch("SELECT id, userid FROM village");
  $loaded_users = array();
  while($usrs = $result->fetch_array()) {
    $reload->bh($usrs['id']);
    if(!in_array($usrs['id'], $loaded_users)) {
      $reload->ressourcereload($usrs['userid']);
      $reload->user_points($usrs['userid']);
      $loaded_users[] = $usrs['userid'];
    }
  }
  $reload->all_ally_points();
exit;
  $error = "Alles refrescht!";
  }
?>
<b><a href="<?= $config['url'] ?>index.php?page=admin" target="blank">Weltadmin</a></b><br>
<form method="post" action="#"><input type="text" name="value" size="1" value="0"><input type="submit" name="free_vil" value="Freie Dörfer"></form>
<b>Mulit Nachricht versenden</b>
<?
if(isset($_POST['send'])) {
  $result= $db->fetch('SELECT id FROM user');
  while ($player = $result->fetch_array()) {
    $db->no_query("INSERT INTO `message` (`userid`, `von_player`, `betreff`, `message`, `readed`, `time`) VALUES
('".$player['id']."', 'Admin', '".$_POST['betreff']."', '".$_POST['message']."', '0', '$zeit');");
    $db->no_query("UPDATE user SET `message` = '1' WHERE id = '".$player['id']."'");
  }
  $no_error;
}
?>
<form method="post" action="#">
Betreff: <input type="text" name="betreff">
Nachricht: <textarea cols="70" rows="21" name="message"></textarea>
<input type="submit" name="send" value="senden">
</form>
<?
if(isset($_POST['lookrep'])) {
$return_error = "game.php?village=".$hid."&page=report&show=view&msid=".$_POST['id'];
}
?>
<? if($user['admin'] > 1) { ?>
<b>Bericht einsehen:</b>
<form method="post" action="#">ID: <input type="text" size="1" name="id"><input type="submit" name="lookrep">
</form>
<? } ?>
<form method="post" action="#"><input type="submit" name="reloadal" value="alle Punkte und speicher usw. neu laden!"></form>
<?
}
?>
