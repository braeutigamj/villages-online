<?php
if(isset($_GET['show']) and $_GET['show'] == "attack") {
  $and = "AND (type = 'attack_lost' or type = 'attack_won' or type = 'attack_won_mountain' or type = 'attack_lost_mountain')";
} elseif(isset($_GET['show']) and $_GET['show'] == "market") {
  $and = "AND (type = 'market_send' or type = 'market_get')";
} elseif(isset($_GET['show']) and $_GET['show'] == "ally") {
  $and = "AND (type= 'invite' or type = 'al_delete' or type = 'drop_out')";
} elseif(isset($_GET['show']) and $_GET['show'] == "friend") {
  $and = "AND (type = 'friend_delete' or type = 'friend_get' or type = 'friend_accept')";
} else {
  $and = "";
}
$result = $db->fetch("SELECT * FROM report WHERE userid='$id' ".$and." ORDER BY id DESC");
?>
<form method="post" action="#">
<table class="vis" width="100%"><tr><th>L&ouml;schen</th><th>Betreff</th><th>Zeit</th></tr>
<?php while ($zeile = $result->fetch_array()) {
  if(isset($_POST['delete']) and isset($_POST[$zeile['id']]) and $_POST[$zeile['id']] == $zeile['id']) {
    $db->no_query("DELETE FROM report WHERE userid='$id' AND id = '".$zeile['id']."'");
    $no_error = true;
  } ?>
<tr><td><input type="checkbox" name="<?php echo $zeile['id']; ?>" value="<?php echo $zeile['id']; ?>"></td><td><a href="game.php?village=<?= $hid ?>&page=report&show=view&msid=<?php echo $zeile['id']; ?>&read=<?php echo $zeile['readed']; ?>"><?php echo message_type($zeile['type']); if($zeile['readed'] == "0") { echo '<img src="'.$config["cdn"].'img/report.png">'; } ?></a></td><td><?php echo format_date($zeile['time']); ?></td></tr>
<?php } ?>
<tr><td colspan="3"><input type="submit" name="delete" value="l&ouml;schen"></td></tr>
</table>
</fom>
