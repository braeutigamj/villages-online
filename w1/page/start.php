<h3>Wichtige Meldung</h3>
<? if(!empty($config['error'])) {
	echo $config['error'];
} else {
	echo "<p>Das Spiel wurde pausiert, dies ist z.B. bei Fehlern oder kurz nach/vor Rundenneustart der Fall.</p>";
}
?>
