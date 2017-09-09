<?
$zeit = time();
require_once "incl/function.php";
require_once "config.php";
$db = new db('w1');
$dblogin = new db("login");
$reload = new reload;
$foot = new foot;
$session = new session;
$id = $session->check();
if(!is_numeric($id)) {
	header("Location: ".$config['url']);
	exit;
} else {
	header("Location: game.php");
	exit;
}
?>
