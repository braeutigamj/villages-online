<? if(isset($_POST['settings'])) {
  if(isset($_POST['village_style'])) { $style = 0; } else { $style = 1; }
  if(isset($_POST['main_show'])) { $main_show = 0; } else { $main_show = 1; }
  if(isset($_POST['extra_show'])) { $extra_show = 0; } else { $extra_show = 1; }
  if(isset($_POST['build_show'])) { $build_show = 0; } else { $build_show = 1; }
  $sql = "UPDATE user SET village_style = '$style', main_show = '$main_show', extra_show = '$extra_show', `ptext` = '".$_POST['ptext']."', `build_show` = '$build_show'";
  if(!empty($_FILES['logo']['tmp_name']) and $_FILES['logo']['size'] <= 60000) {
    $typ = explode(".", $_FILES['logo']['name']);
    $picname = $user['name'].$user['id'].spezielstring().".".end($typ);
    move_uploaded_file($_FILES['logo']['tmp_name'], "upload/".$picname);
    $sql .= ", `logo` = '$picname'";
    $_POST['logouse'] = "aaaa";
  }
  if($_POST['logouse'] == "not") {
    $sql .= ", `logo` = ''";
  }
  $sql .= " WHERE id = '$id'";
  $db->no_query($sql);
  $no_error = true;
}
?>
<script type='text/javascript'>
function count(val) {
   document.getElementById('ausgabe').innerHTML = val.length + " Zeichen wurden eingegeben";
}
</script>
<center>
<a href="game.php?village=<?= $hid ?>&page=settings">Allgemeine Einstellungen</a> -
<a href="<?= $config['url'] ?>index.php?page=setting" target="_blank">
  Weltübergreifende Einstellungen
</a>
<hr />
<form method="post" action="#" enctype="multipart/form-data">
<table class="vis" width="70%"><tr><th colspan="2">Einstellungen</th></tr>
<tr><td>Dorfübersicht:</td><td><input type="checkbox" name="village_style" <? if($user['village_style'] == "0") { echo "checked"; } ?>> grafische Dorfübersicht</td></tr>
<tr><td>Ausgebaute Gebäude:</td><td><input type="checkbox" name="main_show" <? if($user['main_show'] == "0") { echo "checked"; } ?>> anzeigen</td></tr>
<tr><td>Alle Gebäude anzeigen:</td><td><input type="checkbox" name="build_show" <? if($user['build_show'] == "0") { echo "checked"; } ?>> anzeigen</td></tr>
<tr><td>zusätzliche Menüleiste:</td><td><input type="checkbox" name="extra_show" <? if($user['extra_show'] == "0") { echo "checked"; } ?>> anzeigen</td></tr>
<tr><th colspan="2">Persönliches Logo:</th></tr>
<tr><td>Logo:</td><td><input type="file" name="logo" accept="image/*"></td></tr>
<tr><td>Logo aktivieren:</td><td><input type="radio" name="logouse" value="true" <? if(!empty($user['logo'])) { ?>checked<? } ?>>ja <input type="radio" name="logouse" value="not" <? if(empty($user['logo'])) { ?>checked<? } ?>>nein</td></tr>
<tr><th colspan="2">Persönlicher Text:</th></tr>
<tr><td colspan="2"><textarea name="ptext" id="ptext" onkeyup="count(this.value);" cols="72" rows="12"><? echo $user['ptext']; ?></textarea></td></tr>
<tr><td colspan="2"><script>var a = ""; showSmileys('ptext');</script></td></tr>
<tr><td colspan="2"><h6>Maximal 250 Zeichen!<br /><script type="text/javascript">document.write("<div id='ausgabe'></div>");</script></h6></td></tr>
<tr><td colspan="2"><input type="submit" name="settings" value="aktualisieren"></td></tr>
</table>
</form>
<? /*
if(isset($_POST['uvstart'])) {
  $uvid = $db->query("SELECT id FROM user WHERE name='".$_POST['user']."'");
  if($uvid <= 0) {
  $error = "Spieler konnte nicht gefunden werden!";
  } elseif($uvid == $id) {
  $error = "Du kannst dich nicht selbst zum Vertretter ernennen!";
  } else {
    $add = $id.",";
    $db->no_query("UPDATE `user` SET `uvs` = `uvs`.'$add' WHERE id = '$uvid'");
    $db->no_query("INSERT INTO `report`(`userid`, `type`,`time`) VALUES ('$uvid', 'uv', '$zeit')");
    $no_error = true;
  }
}

<form method="post" action="#">
<table class="vis" width="70%"><tr><th colspan="2">Urlaubsvertrettung</th></tr>
<tr><td colspan="2">Solltest du z.B. in den Urlaub fahren kannst du hier eine Urlaubsvertrettung ernennen. Diese kann sich dan ohne dein Passwort von deinem Account aus bei dir einloggen.</td></tr>
<tr><td>User: <input type="text" name="user"></td><td><input type="submit" name="uvstart" value="Urlaubsvertretter ernennen"></td></tr>
</table>
</form>
*/
if(($user['restart']+432000) < $zeit) {
  if(isset($_POST['delete'])) {
    if(md5($config['sec_pw'].$_POST['password'].$config['sec_pwz']) !=
       $dblogin->query("
          SELECT `password`
          FROM `login`
          WHERE `id` = '".$user['global_id']."'")) {
      $error = "Passwort ist falsch!";
    } else {
      $db->no_query("
          UPDATE village
          SET `userid` = '-1',
          `name` = 'Freies+Dorf'
          WHERE userid = '$id'");
      $error = "Du kannst jetzt ein neues Dorf erstellen!";
    }
  }
?>
<form method="post" action="#">
  <table class="vis" width="70%">
    <tr><th colspan="2">Neu starten</th></tr>
    <tr>
      <td colspan="2">
        Solltest du wirklich keine Chance mehr haben, kannst du neu anfangen.
      </td>
    </tr>
    <tr><td>Passwort: <input type="password" name="password"></td><td><input type="submit" onclick="return confirm('Wirklich neu anfangen?');" name="delete" value="Neu starten"></td></tr>
    <tr><th colspan="2">User Werben</th></tr>
    <tr>
      <td colspan="2">
        Bitte werbe möglichst viele User um den Spielspaß noch zu vergrößern.
        <br />
        <a href="<?= $config['help'] ?>help.php?help=adspot" target="_blank">
          Wir bieten dir hierfür auch Werbemittel an!
        </a>
      </td>
    </tr>
    <tr><td colspan="2"><input type="text" value="<?= $config['url'] ?>" size="20"></td></tr>
  </table>
</form>
</center>
<? } ?>
