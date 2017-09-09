<h2>Farm (Stufe <? echo $village['farm']; ?>)</h2>
<p>Die Farm produziert Nahrungsmittel für deine Dorfbewohner, je höher du dieses Gebäude ausbaust, desto mehr Nahrungsmittel werden produziert.</p>
<b>Produktion:</b>
<?
$farm = round($village['farm'] * 2400);
$farmz = round(($village['farm']+1) * 2400);
$farmd = round(($village['farm']+2) * 2400);
?>
<table>
<tr><td>aktuelle Produktion:</td><td><b><? echo $farm; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['farm']+1; ?>:</td><td><b><? echo $farmz; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['farm']+2; ?>:</td><td><b><? echo $farmd; ?></b></td></tr>
</table>
