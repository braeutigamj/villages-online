<?
if(isset($_GET['edit'])) {
include "page/ally/edit.php";
} else { ?>
<form method="post" action="#">
<table class="vis" width="100%"><tr><th>Rang</th><th>Spieler</th><th>Punkte</th><th>Dörfer</th><th>Level</th><? if($level > 1) { ?><th>User bearbeiten</th><? } ?></tr>
<?
$i = 1;
$result = $db->fetch("SELECT * FROM user, ally_user_list WHERE ally_user_list.ally_id = '".$ally['id']."' AND ally_user_list.player_id = user.id ORDER BY points DESC");
while ($users = $result->fetch_array()) {
$usersvil = getvillages($users['player_id']);
?>
<tr><td><? echo $i; ?>.</td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<? echo $users['player_id']; ?>"><? if($users['admin'] == 2) { echo "<font color='red'>"; }
elseif($users['admin'] == 1) { echo "<font color='green'>"; }
echo $users['name']; if($users['admin'] > 0) { echo "</font>"; } ?></a></td><td><? echo $users['points']; ?></td><td><? echo $usersvil; ?></td><td><img src="<?= $config['cdn'] ?>img/level/level<?= $users['level']; ?>.png"></td><? if($level > 1) {  ?><td><a href="game.php?village=<?= $hid ?>&page=ally&show=members&edit&user=<? echo $users['player_id']; ?>">bearbeiten</a></td><? } ?></tr>

<? $i++; } ?></table></form>
</center>
<table class="vis"><tr><th colspan="2">Level-Beschreibung</th></tr>
<tr><td><img src="<?= $config['cdn'] ?>img/level/level0.png"></td><td>Standartmitglied</td></tr>
<tr><td><img src="<?= $config['cdn'] ?>img/level/level1.png"></td><td>Forums-Moderator</td></tr>
<tr><td><img src="<?= $config['cdn'] ?>img/level/level2.png"></td><td>Allianzführung</td></tr>
<tr><td><img src="<?= $config['cdn'] ?>img/level/level3.png"></td><td>Allianzgründer</td></tr>
</table>
<center>
<? } ?>
