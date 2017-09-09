<h2>Holzfäller (Stufe <? echo $village['wood']; ?>)</h2>
<p>Der Holzfäller produziert Holz für dein Dorf, je höher du dieses Gebäude ausbaust, desto mehr Holz wird produziert.</p>
<b>Produktion:</b>
<?
$wood = round($village['wood'] * 2.69 * $village['wood'] * $config['speed']);
$woodz = round(($village['wood']+1) * 2.69 * ($village['wood']+1) * $config['speed']);
$woodd = round(($village['wood']+2) * 2.69 * ($village['wood']+2) * $config['speed']);
?>
<table>
<tr><td>aktuelle Produktion:</td><td><b><? echo $wood; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['wood']+1; ?>:</td><td><b><? echo $woodz; ?></b></td></tr>
<tr><td>Produktion Stufe <? echo $village['wood']+2; ?>:</td><td><b><? echo $woodd; ?></b></td></tr>
</table>
