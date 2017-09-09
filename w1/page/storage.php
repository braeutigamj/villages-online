<h2>Speicher (Stufe <? echo $village['storage']; ?>)</h2>
<p>Im Speicher weren deine Rohstoffe gespeichert, je höher du dieses Gebäude ausbaust, desto mehr Rohstoffe werden gespeichert.</p>
<b>Produktion:</b>
<?
$storage = round($village['storage'] * 3000);
$storagez = round(($village['storage']+1) * 3000);
$storaged = round(($village['storage']+2) * 3000);
?>
<table>
<tr><td>aktuelle Speicherkapazität:</td><td><b><? echo $storage; ?></b></td></tr>
<tr><td>Speicherkapazität Stufe <? echo $village['storage']+1; ?>:</td><td><b><? echo $storagez; ?></b></td></tr>
<tr><td>Speicherkapazität Stufe <? echo $village['storage']+2; ?>:</td><td><b><? echo $storaged; ?></b></td></tr>
</table>
