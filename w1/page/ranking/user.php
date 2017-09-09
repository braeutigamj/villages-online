<table class="vis" width="100%"><tr><th>Rang</th><th>Spieler</th><th>Punkte</th><th>Dörfer</th><th>Punktedurchschnitt pro Dorf</th></tr>
<?
if(isset($_GET['site'])) {
	$site = $_GET['site'];
} else {
	$site = 1;
}
$flimit = ($site-1) * $config['ranksite'];
$llimit = $site * $config['ranksite'];
$i = 1;
$result = $db->fetch("SELECT * FROM user ORDER BY points DESC LIMIT $flimit, $llimit");
while ($users = $result->fetch_array()) {
$usersvil = getvillages($users['id']);
if($usersvil == 0) { $durch = 0; } else {
$durch = round($users['points']/$usersvil); }
if($id == $users['id']) {
?>
<tr><th><? echo $i; ?>.</th><th><a href="game.php?village=<?= $hid ?>&page=splayer&id=<? echo $users['id']; ?>"><? echo $users['name']; ?></a></th><th><? echo $users['points']; ?></th><th><? echo $usersvil; ?></th><th><? echo $durch; ?></th></tr>
<? } else { ?>
<tr><td><? echo $i; ?>.</td><td><a href="game.php?village=<?= $hid ?>&page=splayer&id=<? echo $users['id']; ?>"><? echo $users['name']; ?></a></td><td><? echo $users['points']; ?></td><td><? echo $usersvil; ?></td><td><? echo $durch; ?></td></tr>
<? } ?>

<? $i++; } ?><tr><th colspan="3"><? if($site > 1) { ?><a href="game.php?village=<?= $hid ?>&page=ranking&show=ally&site=<?= $site-1; ?>">nach vorne</a><? } ?></th><th colspan="2"><a href="game.php?village=<?= $hid ?>&page=ranking&show=ally&site=<?= $site+1; ?>">nach hinten</a></th></tr></table>
