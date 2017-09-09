<? if($village['market'] > 0) { ?>
<h2>Marktplatz (Stufe <? echo $village['market']; ?>)</h2>
<?
$village['handler'] = freehandler($hid, $village);
?>
<p>Im Marktplatz kannst du mit anderen Spielern handeln, oder deine Rohstoffe versenden. Pro Gebäude Level bekommst du 2 weitere Händler! Die maximale Transportmenge eines Händlers beträgt 1000 Einheiten bzw. 10 Gold.<br />
Alle Händler: <b><? echo $village['market'] * 2; ?></b><br />
Gesammt Transportmenge: <b><? echo $village['market'] * 2000; ?></b></p>
freie Händler: <b><? echo $village['handler']; ?></b><br />
Rohstoffmenge: <b><? echo $village['handler'] * 1000; ?></b><br /><hr>
<center><a href="game.php?village=<?= $hid ?>&page=market&show=send">Rohstoffe versenden</a> - <a href="game.php?village=<?= $hid ?>&page=market&show=own">eigenes Angebot erstellen</a> - <a href="game.php?village=<?= $hid ?>&page=market&show=other">andere Angebot ansehen</a></center>
<hr />
<? if(!isset($_GET['show'])) { $_GET['show'] = "send"; }	//minus händler unterwegs
include "page/market/".$_GET['show'].".php";
} else { echo "<h2>Gebäude wurde noch nicht gebaut!</h2>"; } ?>
