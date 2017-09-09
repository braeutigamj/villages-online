<?
if(isset($_REQUEST['fbid'])) {
	$fbid = $_REQUEST['fbid'];
} else {
	$fbid = "0";
}
if(isset($_REQUEST['email'])) {
	$fbemail = $_REQUEST['email'];
} else {
	$fbemail = "";
}
if(isset($_REQUEST['user'])) {
	$fbuser = $_REQUEST['user'];
} else {
	$fbuser = "";
}
if(isset($_POST['reg'])) {
	if(empty($_POST['user']) || empty($_POST['email']) || empty($_POST['pasword']) || empty($_POST['paswordz'])) {
		$error = "Bitte fülle alle Felder aus!";
	}
	elseif($_POST['user'] == "Username") {
		$error = "Username ist nicht zulässig!";
	}
	elseif(@$_POST['pasword'] == @$_POST['paswordz']) {
		//testen ob name bereits vorhande
		$exist = $dblogin->query("SELECT count(id) FROM login WHERE name = '".@$_POST['user']."'");
		if($exist >= 1) {
			$error = "Benutzername bereits vorhanden!";
		} else {
			$password = md5($config['sec_pw'].$_POST['pasword'].$config['sec_pwz']);
			if($_POST['fbid'] <= 0) {
				$activate = spezielstring(10);
			} else {
				$activate = 0;
			}
			$dblogin->no_query("INSERT INTO login (name, email, password, activate, fbid) VALUES ('".@$_POST['user']."', '".@$_POST['email']."', '$password', '$activate', '".$_POST['fbid']."')");
			/*$text = "Hallo ".$_POST['user'].",
schön, dass du dich entschlossen hast bei uns Mitzuspielen. Bitte besätige nun noch deinen Account.
Dein Bestätigungscode lautet: ".$activate."
Bitte rufe folgende Url in deinem Browser auf: ".$config['url']."?page=activate&user=".$_POST['user']."&code=".$activate."
Oder fülle das Formular auf folgender Seite aus: ".$config['url']."?page=activate
Mit freundlichen Grüßen
dein ".$config['name']." Team";*/
			$pw = substr($_POST['pasword'], 0, -5);
			$pw .= "*****";
			$text = file_get_contents($config['url']."mail.php?user=".$_POST['user']."&pw=".$pw."vo&mail=".$_POST['email']."&activate=".$activate);  
			mail($_POST['email'], 'Bestätige deinen Account!', $text, "From: ".$config['name']." (NoReplay) <".$config['email'].">");
			$userid = $dblogin->query("SELECT id FROM `login` WHERE name = '".$_POST['user']."' AND password = '$password'");
			$db->no_query("INSERT INTO user (name, global_id, message) VALUES ('".$_POST['user']."', '".$userid."', '1')");
			$player = $db->query("SELECT id FROM `user` ORDER BY id DESC LIMIT 1");
			$session->create($userid);
			$session->create($player, false, "w1");
			if($_POST['fbid'] <= 0) {
				header("Location: ".$config['w1']."game.php?page=start&mail");
			} else {
				header("Location: ".$config['w1']."game.php?page=start");
			}
		}
	} else {
		$error = "Passwörter stimmen nicht überein!";
	}
}
?>
		<h1>Jetzt kostenlos Mitspielen!</h1>
		<p><a href="fb.php"><img src="<?= $config['cdn'] ?>img/stuff/fblogin.png" /></a> oder <br />
<form method="post" name="registration" action="#" onsubmit="return validate(); return false">
<input type="text" name="fbid" value="<?= $fbid ?>" hidden />
<table>
<tr><td>Username: </td><td><input type="text" name="user" value="<?= $fbuser ?>"></td><td rowspan="6"><img src="<?= $config['cdn'] ?>img/pic/register.png"></td></tr>
<tr><td>Email: </td><td><input type="text" name="email" value="<?= $fbemail ?>"></td></tr>
<tr><td>Passwort: </td><td><input type="password" name="pasword"></td></tr>
<tr><td>Passwort wdh.: </td><td><input type="password" name="paswordz"></td></tr>
<tr><td colspan="2">Mit der Registrierung werden die <a href="index.php?page=rules" target="_blank">Regeln</a> akzeptiert!</td></tr>
<tr><td colspan="2"><input type="submit" name="reg" value="Registrieren"></td></tr>
</table>
</form>
<script>
function validate() {
	if (document.registration.user.value == "" || document.registration.password.value == "" || document.registration.passwordz.value == "" || document.registration.email.value == "") {
		alert('Bitte fülle alle Felder mit * aus!');
		document.registration.password.focus();
		return false;
	}
	if(document.registration.password.value != document.registration.passwordz.value) {
		alert('Du must 2 mal das gleiche Passwort eingeben!');
		return false;
	}
	if(document.registration.user.value.charAt(i) < 5) {
		alert('Benutzernamme muss mindestens 5 Zeichen und darf maximal 13 Zeichen lang sein!');
		return false;
	}
	if(document.registration.email.value.search("@") == -1) {
		alert('Bitte gib eine echte Email ein! Sie wird für deine Account Bestätigung benötigt!');
		return false;
	}
}
</script>
