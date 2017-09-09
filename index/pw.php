<?
if(isset($_POST['pw'])) {
	$correct = $dblogin->query("SELECT id FROM login WHERE name = '".$_POST['user']."' AND email = '".$_POST['email']."'");
	if($correct >= 1) {
		$new_pw = spezielstring();
		$dblogin->no_query("UPDATE login SET password = '".md5($config['sec_pw'].$new_pw.$config['sec_pwz'])."' WHERE name = '".$_POST['user']."'");
		$text = "Hallo ".$_POST['user'].",
dein Passwort in deinem Nutzeraccount wurde soeben geändert. Solltest nicht du diese Anfrage gesendet haben, so melde dies bitte den Team!
Benutzername: ".$_POST['user']."
Passwort: ".$new_pw;
		mail($_POST['email'], 'Neues Passwort erhalten!', $text, "From: NoReplay <".$config['email'].">");
		$error = "Passwort wurde geändert. Du hast eine Email mit dem neuen Passwort erhalten!";
	} else {
		$error = "Es konnte kein Account mit dieser Email gefunden werden!";
	}

}
else {
?>
<center>
<h1>Passwort vergessen?</h1>
<p>Hier kannst du dir ein neues Passwort via Email zusenden lassen!</p>
<form method="post" action="#">
<table>
<tr><td>Benutzername: </td><td><input type="text" name="user"></td></tr>
<tr><td>Email: </td><td><input type="text" name="email"></td></tr>
<tr><td><input type="submit" name="pw" value="Passwort vergessen"></td></tr>
</table>
</form>
<br /><br />
<h2><a href="index.php">Zur Startseite!</a></h2>
</center>
<? } ?>
