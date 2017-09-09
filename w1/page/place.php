<? if($village['place'] > 0) { ?>
<h2>Dorfplatz</h2>
<p>Hier kannst du deine Einheiten auf einem Kampf schicken.</p>
<table><tr><td width="120px"><a href="game.php?village=<?= $hid ?>&page=place&show=overview">Truppen</a>
<br /><a href="game.php?village=<?= $hid ?>&page=place&show=befehl">Befehle</a>
<br /><a href="game.php?village=<?= $hid ?>&page=place&show=simulator">Angriffs-Simulator</a></td><td>
<?
if(!isset($_GET['show']) || empty($_GET['show'])) { $_GET['show'] = "overview"; }
include "page/place/".$_GET['show'].".php";
?>
</td></tr></table>
<? } else { echo "<h2>Gebäude wurde noch nicht gebaut!</h2>"; } ?>
