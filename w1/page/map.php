<center>
<a href="game.php?village=<?= $hid ?>&page=map&show=overview">Karte</a> - <a href="game.php?village=<?= $hid ?>&page=map&show=free">Freie Dörfer</a> - <a href="game.php?village=<?= $hid ?>&page=map&show=other">Andere Spieler</a> - <a href="game.php?village=<?= $hid ?>&page=map&show=yours">Deine Dörfer</a>
<hr />
<?
if(isset($_GET['show']) and $_GET['show'] == "free") {
	include "page/map/free.php";
} elseif(isset($_GET['show']) and $_GET['show'] == "other") {
	include "page/map/other.php";
} elseif(isset($_GET['show']) and $_GET['show'] == "yours") {
	include "page/map/yours.php";
} else {
	include "page/map/overview.php";
} ?>
</center>
