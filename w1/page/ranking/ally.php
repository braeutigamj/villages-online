<table class="vis" width="100%"><tr><th>Rang</th><th>Allianz</th><th>Punkte</th><th>Dörfer</th><th>Punktedurchschnitt pro Spieler</th></tr>
<?
if(isset($_GET['site'])) {
	$site = $_GET['site'];
} else {
	$site = 1;
}
$flimit = ($site-1) * $config['ranksite'];
$llimit = $site * $config['ranksite'];
$reload->all_ally_points();
$i = 1;
$result = $db->fetch("SELECT * FROM ally ORDER BY points DESC LIMIT $flimit, $llimit");
while ($users = $result->fetch_array()) {
$d = 0;
$usersvil = getvillages($users['id']);
$durch = round($users['points']/$usersvil);
if($allyu['ally_id'] == $users['id']) {
?>
<tr><th><? echo $i; ?>.</th><th><a href="game.php?village=<?= $hid ?>&page=sally&id=<? echo $users['id']; ?>"><? echo $users['ally_short']; ?></a></th><th><? echo $users['points']; ?></th><th><? echo $usersvil; ?></th><th><? echo $durch; ?></th></tr>
<? } else { ?>
<tr><td><? echo $i; ?>.</td><td><a href="game.php?village=<?= $hid ?>&page=sally&id=<? echo $users['id']; ?>"><? echo $users['ally_short']; ?></a></td><td><? echo $users['points']; ?></td><td><? echo $usersvil; ?></td><td><? echo $durch; ?></td></tr>
<? $i++; } } ?>
<tr><th colspan="3"><? if($site > 1) { ?><a href="game.php?village=<?= $hid ?>&page=ranking&show=ally&site=<?= $site-1; ?>">nach vorne</a><? } ?></th><th colspan="2"><a href="game.php?village=<?= $hid ?>&page=ranking&show=ally&site=<?= $site+1; ?>">nach hinten</a></th></tr>
</table>
