Hier werden alle deine Freunde aufgelistet.
<form method='post' action='#'><table class="vis">
<tr><th>Name</th><th>Zuletzt aktiv</th><th>löschen</th></tr>
<?
if(isset($_REQUEST['add'])) {
  $addplayerid = $db->query("SELECT `id` FROM `user` WHERE `name` = '".$_REQUEST['add']."'");
  if($db->query("SELECT count(id) FROM `friends` WHERE (`user1` = '$addplayerid' AND `user2` = '$id') OR (`user2` = '$addplayerid' AND `user1` = '$id')") <= 0) {
    $db->no_query("INSERT INTO `friends`(`user1`, `user2`, `akzeptiert`, `since`) VALUES ('$id', '$addplayerid', '0', '$zeit')");
    $db->no_query("INSERT INTO `report`(`userid`, `type`, `time`) VALUES ('$addplayerid', 'friend_get', '$zeit')");
  }
}
$result = $db->fetch("SELECT * FROM `friends` WHERE `akzeptiert` = 1 AND (`user1` = '".$user['id']."' OR `user2` = '".$user['id']."')");
while($friend = $result->fetch_array()) {
  if(isset($_POST['drop_'.$friend['id']])) {
    $db->no_query("DELETE FROM `friends` WHERE `id` = '".$friend['id']."'");
    $db->no_query("INSERT INTO `report`(`userid`, `type`, `time`) VALUES ('".$friend['user1']."', 'friend_delete', '$zeit')");
    $db->no_query("INSERT INTO `report`(`userid`, `type`, `time`) VALUES ('".$friend['user2']."', 'friend_delete', '$zeit')");
    $no_error = true;
  }
  echo "<tr><td>";
  if($friend['user1'] != $id) {
    $lid = $friend['user1'];
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user1']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user1']."'")."</a>";
  } else {
    $lid = $id;
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user2']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user2']."'")."</a>";
  }
  echo "</td>";
  echo "<td>".format_date($db->query("SELECT `last_activity` FROM `user` WHERE id = '".$lid."'"))."</td>";
    echo "<td><input type='submit' name='drop_".$friend['id']."' value='Freund löschen'></td>";
  echo "</tr>";
}
echo "</table></form>";
if($db->query("SELECT count(id) FROM `friends` WHERE `akzeptiert` = 0 AND `user2` = '".$user['id']."'") > 0) {
echo "<hr class='content'><b>zu bestätigende Anfragen</b>";
$result = $db->fetch("SELECT * FROM `friends` WHERE `akzeptiert` = 0 AND `user2` = '".$user['id']."'");
echo "<form method='post' action='#'><table>";
while($friend = $result->fetch_array()) {
  if(isset($_POST['drop_'.$friend['id']])) {
    $db->no_query("DELETE FROM `friends` WHERE `id` = '".$friend['id']."'");
    $no_error = true;
  }
  if(isset($_POST['acc_'.$friend['id']])) {
    $db->no_query("UPDATE `friends` SET `akzeptiert` = 1 WHERE `id` = '".$friend['id']."'");
    $db->no_query("INSERT INTO `report`(`userid`, `type`, `time`) VALUES ('".$friend['user1']."', 'friend_accept', '$zeit')");
    $no_error = true;
  }
  echo "<tr><td>";
  if($friend['user1'] != $id) {
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user1']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user1']."'")."</a>";
  } else {
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user2']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user2']."'")."</a>";
  }
  echo "</td><td><input type='submit' name='drop_".$friend['id']."' value='Anfrage löschen'><input type='submit' name='acc_".$friend['id']."' value='Anfrage akzeptieren'></tr>";
}
echo "</table></form>";
}
if($db->query("SELECT count(id) FROM `friends` WHERE `akzeptiert` = 0 AND `user1` = '".$user['id']."'") > 0) {
echo "<hr class='content'><b>gesendete Anfragen</b>";
$result = $db->fetch("SELECT * FROM `friends` WHERE `akzeptiert` = 0 AND `user1` = '".$user['id']."'");
echo "<form method='post' action='#'><table>";
while($friend = $result->fetch_array()) {
  if(isset($_POST['drop_'.$friend['id']])) {
    $db->no_query("DELETE FROM `friends` WHERE `id` = '".$friend['id']."'");
    $no_error = true;
  }
  echo "<tr><td>";
  if($friend['user1'] != $id) {
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user1']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user1']."'")."</a>";
  } else {
    echo "<a href='game.php?village=".$hid."&page=splayer&id=".$friend['user2']."'>".$db->query("SELECT `name` FROM `user` WHERE id = '".$friend['user2']."'")."</a>";
  }
  echo "</td><td><input type='submit' name='drop_".$friend['id']."' value='Anfrage löschen'></tr>";
}
echo "</table></form>";
}
?>
<br />
Anfrage versenden: <form method="post" action="#"><input type="text" name="add"><input type="submit" name="adduser" value="User hinzufügen"></form>
