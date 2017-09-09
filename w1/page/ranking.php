<? if(!isset($_GET['show'])) {
$_GET['show'] = "user"; } ?><h2>Rangliste</h2>
<center><a href="game.php?village=<?= $hid ?>&page=ranking&show=user">User</a> - <a href="game.php?village=<?= $hid ?>&page=ranking&show=ally">Allianz</a></center><hr />
<?
$file="page/ranking/".$_GET['show'].".php";
if (file_exists($file)) {
include $file;
}
else
{
include "page/ranking/user.php";
}
?>
