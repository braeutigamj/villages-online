<?
if(isset($_REQUEST['user']) and isset($_REQUEST['code'])) {
	$correct = $dblogin->query("SELECT count(id) FROM login WHERE name = '".$_REQUEST['user']."' AND activate = '".$_REQUEST['code']."'");
	if($correct >= 1) {
		$dblogin->no_query("UPDATE login SET activate = '0' WHERE name = '".$_REQUEST['user']."'");
		$error = "Account wurde aktiviert! Du kannst dich auf der Startseite nun einloggen!";
	} else {
		$error = "Account bereits aktiviert oder Aktivierungscode ist falsch!";
	}

}
else {
?>
<div align="center">
<h1>Account aktivieren</h1>
<form method="post" action="#">
<table>
<tr><td>Benutzername: </td><td><input type="text" name="user"></td></tr>
<tr><td>Aktivierungscode*: </td><td><input type="text" name="code"></td></tr>
<tr><td colspan="2"><input type="submit" name="submit" value="Account aktivieren"></td></tr>
</table>
</form>
*Dieser Code wurde ihnen per Email zugesendet.
</div>
<? } ?>
