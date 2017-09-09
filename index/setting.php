<?
if(isset($userid)) {
$user = $dblogin->assoc("SELECT * FROM login WHERE id = '".$userid."'");
$id = $userid;
header("Content-Type: text/html; charset=utf-8");
echo "<!DOCTYPE html>";
echo "<head>";
	echo "<title>".$config['name']."</title>";
	echo '<link rel="stylesheet" href="design/style.css" type="text/css">';
	echo "<link href='img/game.css' rel='stylesheet' type='text/css' />";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '</head>';
echo '<body>';
if(isset($_POST['password']))
{
	$old_pw = $_POST['old_pw'];
	$old_pw = md5($config['sec_pw'].$old_pw.$config['sec_pwz']);
	if($old_pw == $user['password'])
	{
		$new_pw = $_POST['new_pw'];
		$new_pw2 = $_POST['new_pw2'];
		if($new_pw == $new_pw2)
		{
			$new_pw = md5($config['sec_pw'].$new_pw.$config['sec_pwz']);
			$dblogin->no_query("UPDATE login SET password='$new_pw' WHERE id='$id'");
			$error = "Passwort wurde aktualisiert!";
		}
		else
		{
			$error = "Bitte gib zweimal das selbe Passwort ein!";
		}
	}
	else
	{
		$error = "Altes Passwort ist falsch!";
	}
}
if(isset($_POST['email']))
{
	$old_pw = $_POST['password2'];
	$old_pw = md5($config['sec_pw'].$old_pw.$config['sec_pwz']);
	if($old_pw == $user['password'])
	{
		$newemail = $_POST['new_email'];
		$dblogin->no_query("UPDATE login SET email='$newemail' WHERE name='$name'");
		$error = "Email wurde aktualisiert!";
	}
	else
	{
		$error = "Passwort ist falsch!";
	}
}
if(isset($_POST['delete']))
{
	$old_pw = $_POST['password2'];
	$old_pw = md5($config['sec_pw'].$old_pw.$config['sec_pwz']);
	if($old_pw == $user['password'])
	{
		$db->no_query("UPDATE village SET userid='-1', name='Freies Dorf' WHERE userid = '$id'");
		$db->no_query("DELETE FROM user WHERE id = '$id'");
		$session->destroy($id);
		$error = "Account wurde gelöscht! Mit dem nächsten KLick landest du auf der Startseite!";
	}
	else
	{
		$error = "Passwort ist falsch!";
	}
}
?>
<div align="center">
<h2>Account-Center</h2>
<p>Hier kannst du Weltübergreifende und -unabhängige Einstellungen tätigen!</p><br /><br />
<h2>Account-Informationen:</h2>
<table class="vis">
<tr><td>Benutzername:</td><td><?php echo $user['name']; ?></td></tr>
<tr><td>Email:</td><td><?php echo $user['email']; ?></td></tr>
<tr><td>Passwort:</td><td>*****</td></tr>
</table>
<h2>Passwort ändern</h2>
<table class="vis">
<form method="post" action="#">
<tr><td>Altes Passwort: </td><td><input type="password" size="11" name="old_pw"></td></tr>
<tr><td>Neues Passwort: </td><td><input type="password" size="11" name="new_pw"></td></tr>
<tr><td>Neues Passwort wdh.: </td><td><input type="password" size="11" name="new_pw2"></td></tr>
<tr><td colspan="2"><input type="submit" value="ändern" name="password"></td></tr></table>
<h2>Email ändern</h2>
<table class="vis">
<form method="post" action="#">
<tr><td>Passwort: </td><td><input type="password" size="11" name="password2"></td></tr>
<tr><td>Neue Email: </td><td><input type="text" size="11" name="new_email"></td></tr>
<tr><td colspan="2"><input type="submit" value="ändern" name="email"></td></tr></table>
</form>
<h2>Account löschen</h2>
<p>Es wird dein Account auf allen Welten entgültig gelöscht!<br />Dieser Schritt kann nicht rückgängig gemacht werden! Evtl. werden Teile der Daten erst nach Ende der jeweiligen Runde gelöscht!</p>
<table class="vis">
<form method="post" action="#">
<tr><td>Passwort: </td><td><input type="password" size="11" name="password2"></td></tr>
<tr><td colspan="2"><input type="submit" onclick="return confirm('Willst du deinen Account sicher endgültig löschen?');" value="Account entgültig löschen" name="delete"></td></tr></table>
</form>
</div>
<? }
else {
	echo "Accountinformationen konnten nicht geladen werden, bitte versuche es erneut!";
} ?>
