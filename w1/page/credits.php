<?
/********************************************************************************************************************************
ACHTUNG!
DIESE SEITE WIRD AKTUELL NICHT BENÖTIGT UND IST DAHER NICHT MEHR AKTUELL! BITTE ERST AKTUALLISIEREN FALLS WIEDER IN BETRIEBNAHME!
********************************************************************************************************************************/
//Premium-Account
if(isset($_POST['kind'])) {
if($user['credits'] >= 200) {
if($user['pa_account'] <= $zeit) {
$pa = $zeit + 2678400;
} else { $pa = $user['pa_account'] + 2678400; }
//PA-Small auch updaten!
if($user['pa_small'] <= $zeit) {
$pas = $zeit + 2678400;
} else { $pas = $user['pa_small'] + 2678400; }
mysql_query("UPDATE user SET `credits` = credits-200, 'pa_account' = '$pa', `pa_small` = '$pas' WHERE id='$id'");
$_SESSION['pa'] = 1;
$error="Premium-Account gekauft/verlängert!";
} else { $error = "Nicht genügend Credits!"; }
}

//Premium-Small
if(isset($_POST['pa_small'])) {
if($user['credits'] >= 50) {
if($user['pa_small'] <= $zeit) {
$pas = $zeit + 2678400;
} else { $pas = $user['pa_small'] + 2678400; }
mysql_query("UPDATE user SET `credits` = credits-50, `pa_small` = '$pas' WHERE id='$id'");
$error="Small Premium-Account gekauft/verlängert!";
} else { $error = "Nicht genügend Credits!"; }
}
if(isset($_POST['free'])) {
mysql_query("UPDATE user SET `credits` = credits+'100' WHERE id='$id'");
$error="Updated...";
}
?>
<h2>Creditstube</h2>
<p>Um uns über Wasser zu halten müssen leider auch wir Premium-Funktionen anbieten. Hier kannst du dir Credits kaufen und sie einsetzen!</p>
Deine Credits: <b><? echo $user['credits']; ?></b><br />
Premium-Account aktiv bis: <b><? if($user['pa_account'] < $zeit) { echo "deaktiviert"; } else { echo format_date($user['pa_account']); } ?></b><br />
Small-PA aktiv bis: <b><? if($user['pa_small'] < $zeit) { echo "deaktiviert"; } else { echo format_date($user['pa_small']); } ?></b>
<br />
<a href="#popupcredits">Kredits kaufen</a><form method="post" action="#"><input type="submit" name="free" value="free"></form>
<br />
<center>
<hr />
<center>Premium Small Paket wird natürlich bei Kauf eines normalen Premium-Accounts ebenfalls kostenlos verlängert!</center>
<hr />
<form method="post" action="#">
<table class="vis">
<tr><th>Funktion</th><th>Beschreibung</th><th>Preis<br />in Credits</th><th>Kaufen</th></tr>
<tr><td>Premium-Account</td><td>Erhalte eine extra Menüleiste und mehr*</td><td>200 pro Monat</td><td><input type="submit" name="kind" value="kaufen"></td></tr>
<tr><td>Small-PA</td><td>Werbefreiheit, aufklappbares Menü</td><td>50 pro Monat</td><td><input type="submit" name="pa_small" value="kaufen"></td></tr>
</table>
</form>

<b>Funktion des Premium-Accounts</b>
<ul>
<li>Werbefreiheit</li>
<li>aufklappbare Menüleiste</li>
<li>erweiteter Notizblock</li>
<li>farblicher Username</li>
</ul>
