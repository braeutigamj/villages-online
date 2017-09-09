<?
//Hintegrundabfragen
if(isset($_REQUEST['page']) and $_REQUEST['page'] == "index" and isset($_REQUEST['cookie']) and $_REQUEST['cookie'] == "info_world") {
	$expire = time()+99999;
	setcookie($_REQUEST['cookie'], $_REQUEST['active'], $expire);
	echo "true";
} else {
	echo "false";
}
?>
