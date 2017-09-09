<?php
$link = $_GET['url'];
$real = explode("//", $link);
if(!isset($real[1])) {
$link = "http://".$link;
}
$link = str_replace("QUESTIONM", "?", $link);
$link = str_replace("ANDST", "&", $link);
if(isset($_GET['id'])) {
	$dblogin->no_query("UPDATE `addtable` SET `clickcount` = `clickcount`+1 WHERE id = '".$_GET['id']."'");
}
?>
<center><b>Achtung! Sie verlassen jetzt <?= $config['name'] ?>!</b>
<br />
<br />
<br />
<b><?= $config['name'] ?></b> kann keine Verantwortung über die hier gezeigten Inhalte geben!<br />
<h3><u><a href="<?php echo $link; ?>">Weiter</a></u></h3>
</center>
