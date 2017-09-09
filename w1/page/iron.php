<h2>Eisenmine (Stufe <? echo $village['iron']; ?>)</h2>
<p>Die Eisenmine produziert Eisen für dein Dorf, je höher du dieses Gebäude ausbaust, desto mehr Eisen wird produziert.</p>
<b>Produktion:</b>
<?
$iron = round($village['iron'] * 2.69 * $village['iron'] * $config['speed']);
$ironz = round(($village['iron']+1) * 2.69 * ($village['iron']+1) * $config['speed']);
$irond = round(($village['iron']+2) * 2.69 * ($village['iron']+2) * $config['speed']);
?>
<table>
<tr><td>aktuelle Produktion:</td><td><b><? echo $iron; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['iron']+1; ?>:</td><td><b><? echo $ironz; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['iron']+2; ?>:</td><td><b><? echo $irond; ?></b></td></tr>
</table>
