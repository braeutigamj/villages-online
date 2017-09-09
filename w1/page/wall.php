<h2>Wall (Stufe <? echo $village['wall']; ?>)</h2>
<p>Dein Wall erhöht den Verteidigungswert deines Dorfes pro Ausbaustufe! Der Standartwert ist <b><? echo $config['w_bonus']; ?></b>!</p>
<b>Bonus:</b>
<?
$wall = round($village['wall'] * $config['w_bonus'])+$config['w_bonus'];
$wallz = round(($village['wall']+1) * $config['w_bonus'])+$config['w_bonus'];
$walld = round(($village['wall']+2) * $config['w_bonus'])+$config['w_bonus'];
?>
<table>
<tr><td>aktueller Bonus:</td><td><b><? echo $wall; ?></b></td></tr>
<tr><td>Bonus Stufe <? echo $village['wall']+1; ?>:</td><td><b><? echo $wallz; ?></b></td></tr>
<tr><td>Bonus Stufe <? echo $village['wall']+2; ?>:</td><td><b><? echo $walld; ?></b></td></tr>
</table>
