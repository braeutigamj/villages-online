<?php
if ($level >= "1")
{
if($existusers = $db->query("SELECT count(*) FROM `ally_user_list` where ally_id = '$ally_id'") >= $config['max_ally']) {
	echo "<b>Maximales Mitgliederlimit bereits erreicht! Entlasse andere Mitglieder um wieder Spieler einladen zu können</b>";
} else {

if(isset($_POST['invite_player']))
{
$invite_player = $_POST['invite_player'];
$invite_id = $db->query("SELECT id FROM user WHERE name = '$invite_player'");	//PlayerID
if($invite_id == "")
{
$error = "Dieser Spieler existiert nicht!";
}
else
{
$db->no_query("INSERT INTO `ally_invite` (`ally_id`, `player_id`) VALUES
('".$ally['id']."', '$invite_id');");
$db->no_query("INSERT INTO `report`(`userid`, `type`, `booty`, `readed`) VALUES ('".$user['id']."', 'invite', '".$ally['id']."', '0')");
$db->no_query("UPDATE user SET report = '1' WHERE id = '".$user['id']."'");
$error = "Der Spieler ".$invite_player." wurde eingeladen!";
}
}
?>
<p><b>Einladen:</b><br />
<form method="post" action="#">Name: <input type="text" name="invite_player"> <input type="submit" value="Einladen"></form>
</p>
<?php } } ?>
