<h1>Rundenplan</h1>
<p>Hier siehst du die geplannten und vergangenen Runden. Der Rundenneustart erfolgt automatisch!</p>
<table class="vis" width="100%">
<? 
$result = $dblogin->fetch("SELECT * FROM `rounds` ORDER BY ID DESC");
while($rounds = $result->fetch_array()) {
$rconfig = unserialize($rounds['config']);
echo "<th colspan='2'>Runde <i>#".$rounds['id']."</i> auf Welt ".$rounds['world']."</th>"; ?>
<tr><td>Startdatum</td><td><b><?= $rounds['start_date']; ?></b></td></tr>
<tr><td>Startzeit</td><td><b><?= $rounds['start_time']; ?></b></td></tr>
<tr><td>Enddatum</td><td><b><?= $rounds['end_date']; ?></b></td></tr>
<tr><td>Endzeit</td><td><b><?= $rounds['end_time']; ?></b></td></tr>
<tr><td>Konfiguration</td><td>Speed: <b><?= $rconfig['speed']; ?></b><br>Zustimmungsspeed: <b><?= $rconfig['agreement']; ?></b><br>Wall Bonus: <b><?= $rconfig['wall']; ?></b><br>Mitgliederlimit pro Allianz: <b><?= $rconfig['ally']; ?></b><br>mit Tutorial: <b><?= $rconfig['tutorial']; ?></b></td></tr>
<?
}
?>
</table>
