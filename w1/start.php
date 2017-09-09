<?
$zeit = time();
require_once "incl/function.php";
$building = new building;
$units = new units;
require_once "config.php";
$db = new db('w'.$config['id']);
$reload = new reload;
$foot = new foot;
$session = new session;
$tutorial = new tutorial;
$id = $session->check("w".$config['id']);
ob_start();
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='<?= $config['cdn'] ?>style.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="<?= $config['url'] ?>js/jquery.js"></script>
<title>.: <?= $config['name'] ?> :.</title>
</head>
<body>
<br /><br />
<?
if(isset($id)) {
$hid = $db->query("SELECT count(id) FROM village WHERE userid = '".$id."' LIMIT 1");
$user = $db->assoc("SELECT * FROM `user` WHERE id = '".$id."'");
if($hid > 0) {
	header("Location: game.php");
}
if(isset($_POST['country']))
{
	$land = $_POST['land'];
	for($ifexist = 2; $ifexist != 0; ) {
		$koord = showkoord($land);
		$ifexist = $db->query("SELECT count(id) FROM `village` WHERE x = '".$koord['x']."' AND y = '".$koord['y']."'");	//Ob Bereits ein Dorf mit diesen Koordinaten
	}
	$village_name = $user['name']."´s Dorf";
	//Dorf bauen
	$db->no_query("INSERT INTO `village` (`userid`, `name`, `x`, `y`, `wood_r`, `clay_r`, `iron_r`, `ress_times`, `main`, `wood`, `clay`, `iron`, `farm`, `storage`) VALUES ('".$user['id']."', '$village_name', '".$koord['x']."', '".$koord['y']."', '1000', '1000', '1000', '".time()."', '1', '1', '1', '1', '1', '1')");
	$text = "Herzlich Wilkommen in ".$config['name']."!
Schön, dass du zu uns gefunden hast! Vielleicht hast du das Tutorial bereits erledigt? Solltest du weitere Fragen haben, kannst du den Support, deine Mitspieler oder die Hilfeseite um Rat erbitten.
Viel Spaß und viel Erfolg!

Mit freundlichen Grüßen
Admin";
	$db->no_query("INSERT INTO `message`(`userid`, `readed`, `betreff`, `message`, `von_player`, `time`) VALUES ('".$user['id']."', '0', 'Herzlich Wilkommen in ".$config['name']."!', '$text', 'Admin', '$zeit')");
	if(isset($_GET['mail'])) {
		header("Location: game.php?village=".$hid."&game.php?mail");
	} else {
		header("Location: game.php");
	}
}

?>
<div class="body">
<form method="post" action="#">
<table>
<tr>
<td><img src="<?= $config['cdn'] ?>img/pic/start.png"></td>
<td><table class="vis">
<tr><th colspan="2"><h2>Startposition w&auml;hlen</h2></th></tr>
<tr><td>NordWest:</td><td><input type="radio" name="land" value="nw"></td></tr>
<tr><td>S&uuml;dWest:</td><td><input type="radio" name="land" value="sw"></td></tr>
<tr><td>NordOst:</td><td><input type="radio" name="land" value="no"></td></tr>
<tr><td>S&uuml;dost:</td><td><input type="radio" name="land" value="so"></td></tr>
<tr><td>zuf&auml;llig:</td><td><input type="radio" name="land" value="z" checked></td></tr>
<tr><th colspan="2"><input type="submit" name="country" value="Jetzt Starten!"></th></tr>
</table></td>
<td><img src="<?= $config['cdn'] ?>img/stuff/startposition.png"></td>
</tr></table>
</form>
</div><? ob_end_flush();
if(isset($error)) {
?><script>
alert('<? echo $error; ?>');
</script>
<meta http-equiv="refresh" content="0; URL=index.php">
<? } } ?>
<br /><br />
</body>

</html>
