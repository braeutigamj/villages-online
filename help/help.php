<?
$nodb = true;
require_once "../config.php";
header("Content-Type: text/html; charset=utf-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>.: <?= $config['name'] ?> :.</title>
<link href='<?= $config['cdn'] ?>style.css' rel='stylesheet' type='text/css' />
<meta name="author" content="Jonas Bräutigam">
<meta name="description" content="<?= $config['description']; ?>">
<meta name="keywords" content="<?= $config['keyword']; ?>">
</head>
<body>
<table class="body"><tr><td style="vertical-align:top; width: 20%;">
			<h3>Hilfe</h3><ul>
<li><a href="help.php?help=start">Start</a></li>
<li><a href="help.php?help=first">erste Schritte</a></li>
<li><a href="help.php?help=building">Geb&auml;ude</a></li>
<li><a href="help.php?help=units">Einheiten</a></li>
<li><a href="help.php?help=fight">Kampfsystem</a></li>
<? /*<li><a href="help.php?help=mountain">Berge</a></li>*/ ?>
<li><a href="help.php?help=adspot">Werbung</a></li></ul></td><td>
<?
if(isset($_GET['help']))	//Wenn Seite gewählt
{
$file=$_GET['help'].".php";
if (file_exists($file)) {
include $file;
}
else
{
include "start.php";
}
}
else //Default seite
{
include "start.php";
}
?></td></tr>
<tr><td colspan="2"><a href="<?= $config['url'] ?>index.php?page=impressum">Impressum</a> - <?= $config['name'] ?> © 2012 - 20<? echo date("y"); ?></td></tr></table><br /><br /></body>
</html>
