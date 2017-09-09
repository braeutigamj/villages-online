<h2>Lehmgrube (Stufe <? echo $village['clay']; ?>)</h2>
<p>Die Lehmgrube produziert Lehm für dein Dorf, je höher du dieses Gebäude ausbaust, desto mehr Lehm wird produziert.</p>
<b>Produktion:</b>
<?
$clay = round($village['clay'] * 2.69 * $village['clay'] * $config['speed']);
$clayz = round(($village['clay']+1) * 2.69 * ($village['clay']+1) * $config['speed']);
$clayd = round(($village['clay']+2) * 2.69 * ($village['clay']+2) * $config['speed']);
?>
<table>
<tr><td>aktuelle Produktion:</td><td><b><? echo $clay; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['clay']+1; ?>:</td><td><b><? echo $clayz; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['clay']+2; ?>:</td><td><b><? echo $clayd; ?></b></td></tr>
</table>
