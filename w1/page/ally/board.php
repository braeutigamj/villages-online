<?php
if($ally['board'] != "") {
  $no_error = true;
  $return_error = $config['url']."?page=redir&url=".$ally['board'];
} else {
  if(isset($_GET['make_activ'])) {
    $db->no_query("INSERT INTO `ally_categorie`(`ally_id`, `name`) VALUES ('$ally_id', 'Allgemein')");
    header("Location: game.php?village=".$hid."&page=ally&show=board");
  }
  if(!isset($_SESSION['board_admin']) or $_SESSION['board_admin'] == "0") {
    if(isset($_GET['category'])) {
      $category = $_GET['category'];
    } else {
      $categorieeins = $db->query("SELECT id FROM ally_categorie WHERE ally_id = '$ally_id' LIMIT 1");
      if($categorieeins <= 0) {
        $db->no_query("INSERT INTO `ally_categorie`(`ally_id`, `name`) VALUES ('$ally_id', 'Allgemein')");
        $categorieeins = $db->query("SELECT id FROM ally_categorie WHERE ally_id = '$ally_id' LIMIT 1");
      }
      $category = $categorieeins;
    }
    $threads = $db->fetch("SELECT * FROM ally_board_thread WHERE ally_id = '$ally_id' AND ally_categorie = '$category' ORDER BY lastanswer DESC");
    $cates = $db->fetch("SELECT * FROM ally_categorie WHERE ally_id = '$ally_id'");
    echo "<p>";
    $i = 0;
    while ($catesanz = $cates->fetch_array())  //Kategorien
    {
      $i++;
      if($i > 1) {
        echo " - ";
      }
      if($catesanz['id'] == $category) {
        echo "<i>";
      }
?>
<a href="game.php?village=<?= $hid ?>&page=ally&show=board&category=<?php echo $catesanz['id']; ?>"><?php echo $catesanz['name']; ?></a>
<?php if($catesanz['id'] == $category) { echo "</i>"; } } ?></p><hr />
<a href="game.php?village=<?= $hid ?>&page=ally&show=board_new&category=<? echo $category; ?>">Neuen Thread eröffnen</a><br />
<table class="vis" width="100%">
<tr><th>Title</th><th>Erstellt von</th><th>Antworten</th><th>Letzte Aktivität</th></tr>
<?php while ($db_erg = $threads->fetch_array())  //Threads
{
$player_name = $db->query("SELECT name FROM user WHERE id = '".$db_erg['player_id']."'");
?>
<tr><td><a href="game.php?village=<?= $hid ?>&page=ally&show=board_thread&id=<?php echo $db_erg['id']; ?>"><?php echo $db_erg['thread_name']; ?></a></td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<?php echo $player_name; ?>"><?php echo $player_name; ?></a></td><td><?php echo $db_erg['answers']; ?></td><td><?= format_date($db_erg['lastanswer']) ?></td></tr>
<?php } ?>
</table><br />
<?php
if(isset($_POST['admin']) and $level >= "1") {
  $_SESSION['board_admin'] = $level;
  $no_error = true;
}
if($level >= "1") { ?><form method="post" action="#"><input type="submit" name="admin" value="Adminbereicht betretten?"></form><?php }
} else { // AB Hier nur noch wenn man als Admin eingeloggt ist!
  if(isset($_POST['admin2'])) {
    $_SESSION['board_admin'] = "0";
    $no_error = true;
  }
?>
<form method="post" action="#"><input type="submit" name="admin2" value="Adminbereicht verlassen?"></form><br />
<h2>Willkommen im Forumadminbereich!</h2>
<p>Kategorien<br />
Hier kannst du die Kategorien ändern!</p>
<form method="post" action="#">
<table class="vis" id="center"><tr><th>löschen</th><th>Kategoriename</th></tr>
<?php
$cates = $db->fetch("SELECT * FROM ally_categorie WHERE ally_id = '$ally_id'");  //Kategorien anzeigen
while ($catesanz = $cates->fetch_array()) {
  if(isset($_POST['delete'])) {
    $id = $catesanz['id'];
    if(isset($_POST[$id])) {
      $db->no_query("DELETE FROM ally_categorie WHERE id = '$id'");
      $no_error = true;
    }
  } ?>
<tr><td><input type="checkbox" name="<?php echo $catesanz['id']; ?>" value="<?php echo $catesanz['id']; ?>"></td><td><?php echo $catesanz['name']; ?></td></tr>
<?php }
if(isset($_POST['new_kate'])){
  $db->no_query("INSERT INTO `ally_categorie`(`ally_id`, `name`) VALUES ('$ally_id', '".$_POST['new_kate']."')");
  $no_error = true;
}
?>
</table>
<input type="submit" name="delete" value="l&ouml;schen">
</form><form method="post" action="#"> Kategoriename: <input type="text" name="new_kate"><input type="submit" value="erstellen"></form>
<hr>
Mehr Einstellungsmöglichkeiten folgen!
<?php } } ?>
