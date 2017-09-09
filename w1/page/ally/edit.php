<?
if($level > 1) {
  $user_level = $db->query("SELECT level FROM `ally_user_list` WHERE ally_id = '".$ally['id']."' AND player_id = '".$_GET['user']."'");
  if($user_level <= 0) {
    header("Location: 404.php");
    exit;
  }
  if($user_level >= 2 and $level != 3) {
    $error = "Leider kannst du nur als Gründe die Allianzführung ändern!";
  } elseif($_GET['user'] == $id) {
    $error = "Du kannst deine eigenen Berechtigungen nicht ändern!";
    $return_error = "game.php?village=".$hid."&page=ally&show=members";
  } else {
    if(isset($_POST['change'])) {
      $db->no_query("UPDATE `ally_user_list` SET `level` = '".$_POST['new_level']."' WHERE player_id = '".$_GET['user']."'");
      $error = "Userberechtigung wurde geändert!";
    }
    if(isset($_POST['delete'])) {
      $db->no_query("DELETE FROM `ally_user_list` WHERE player_id = '".$_GET['user']."'");
      $reload->ally_points($ally['id']);
      $db->no_query("INSERT INTO `report`(`userid`, `type`) VALUES ('".$_GET['user']."', 'drop_out')");
      $error = "User wurde entlassen!";
    }
    ?><b>Berechtigung des Users ändern:</b>
    <form method="post" action="#">
      <select name="new_level" size="1">
        <option value='0'>Standartmitglied</option>
        <option value='1'>Forums-Moderator</option>
        <? if($level == 3) { ?><option value='2'>Allianzführung</option>
        <option value='3'>Allianzgründer</option><? } ?>
      </select>
      <input type="submit" name="change" value="Berechtigung ändern"><br />
      <b>User entlassen:</b>
      <input type="submit" name="delete" value="User entlassen" onclick="return confirm('User wirklich endgueltig entlassen?!');">
    </form>
<?
  }
}
else
{
  header("Location: 404.php");
}
