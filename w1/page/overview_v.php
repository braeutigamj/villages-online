<?
$result = $db->fetch("SELECT * FROM `group` WHERE userid = '$id'");
?>
<div id="center">
<a href="game.php?village=<?= $hid ?>&page=overview_v&show=building">Gebäude</a> - <a href="game.php?village=<?= $hid ?>&page=overview_v&show=units">Einheiten</a> - <a href="game.php?village=<?= $hid ?>&page=overview_v&show=moving">Truppenbewegungen</a>
<?
while ($groups = $result->fetch_array()) {
?>
 - <a href="game.php?village=<?= $hid ?>&page=overview_v&show=group&id=<? echo $groups['id']; ?>"><? echo $groups['name']; ?></a>
<? } ?>
<hr />
<?
if(isset($_GET['show']))	{
	$file="page/overview/".$_GET['show'].".php";
	if (file_exists($file)) {
		include $file;
	} else {
		include "page/overview/overview.php";
	}
} else {
	include "page/overview/overview.php";
}
?>
</div>
