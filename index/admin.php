<?
$id = $userid;
if(isset($id)) {
$user = $dblogin->assoc("SELECT * FROM login WHERE id = '".$id."'");
if($user['admin'] > 0) {
?>
<h1>Weltübergreifende Administration</h1>
<?
if(isset($_POST['new'])) {
  $dblogin->no_query("INSERT INTO `news`(`time`, `message`) VALUES ('$zeit', '".$_POST['news']."')");
$no_error = "News wurden gepostet!";
}
if(isset($_POST['add'])) {
  $configf['speed'] = $_POST['speed'];
  $configf['agreement'] = $_POST['agreement'];
  $configf['wall'] = $_POST['wall'];
  $configf['ally'] = $_POST['ally'];
  $configf['tutorial'] = $_POST['tutorial'];
  $configf = serialize($configf);
  $dblogin->no_query("INSERT INTO `rounds`(`start_date`, `start_time`, `end_date`, `end_time`, `config`, `world`) VALUES ('".$_POST['start']."', '".$_POST['starttime']."', '".$_POST['end']."', '".$_POST['endtime']."', '$configf', '".$_POST['world']."')");
  $error = "Erfolgreich in Datenbank eingetragen!";
}
if(isset($_POST['adminadd']) and $user['admin'] > 1) {
  $db->no_query("UPDATE `user` SET admin = '".$_POST['level']."' WHERE name = '".$_POST['user']."'");
  $dblogin->no_query("UPDATE `login` SET admin = '".$_POST['level']."' WHERE name = '".$_POST['user']."'");
  $error ="Erfolgreich done";
}
if($user['admin'] > 1) {
?>
<b>Neuen Admin hinzufügen:</b>
<form method="post" action="#">
<select name="level" size="1">
<option value="0">Berechtigung löschen</option>
<option value="1">Standart</option>
<option value="2">Full</option>
</select><input type="text" name="user">
<br><input type="submit" name="adminadd">
</form>
<? } ?>
<b>Ankündigung bearbeiten:</b><br />
<?
$result = $dblogin->fetch("SELECT * FROM `news` ORDER BY id DESC");
while($news = $result->fetch_array()) {
  if(isset($_GET['dank']) and $_GET['dank'] == $news['id']) {
    $dblogin->no_query("DELETE FROM `news` WHERE id = '".$_GET['dank']."'");
  } else {
    echo $news['message'].' <a href="index.php?page=admin&dank='.$news['id'].'">löschen</a><br />';
  }
} ?>
<b>Ankündigung hinzufügen:</b>
<form method="post" action="#"><textarea cols="30" name="news"></textarea><input type="submit" name="new" value="Posten"></form>
<br /><br />
<b>Neuen Rundenplan hinzufügen:</b>
  <form method="post" action="#">
    Startdatum:<input type="date" name="start">
    Startzeit:<input type="datetime" name="starttime">YYYY-MM-DD, HH:MM<br>
    Enddatum:<input type="date" name="end">
    Endzeit:<input type="datetime" name="endtime"><br>
    Config:<br>
    Gamespeed:<input type="text" name="speed" value="1"><br>
    Zustimmungsspeed:<input type="text" name="agreement" value="3600"><br>
    Wall-Bonus:<input type="text" name="wall" value="11"><br>
    Max Ally:<input type="text" name="ally" value="0"><br>
    Tutorial:<input type="text" name="tutorial" value="1"><br>
    Welt: <input type="text" name="world" value="1" size="2"><br />
    <br><input type="submit" name="add">
  </form>
<h2><a href="game.php">Zurück zum Spiel!</a></h2>
<?
}
} ?>
