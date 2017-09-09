<?php
if ($level >= "2") {
	$welcome = $db->query("SELECT welcome FROM ally WHERE id = '$ally_id'");	//alte Wilkommensnachricht
	if(isset($_POST['delete_ally'])) {
		$db->no_query("DELETE FROM ally_invite WHERE ally_id = '$ally_id'");
		$db->no_query("DELETE FROM ally_board_answer WHERE ally_id = '$ally_id'");
		$db->no_query("DELETE FROM ally_board_thread WHERE ally_id = '$ally_id'");
		$db->no_query("DELETE FROM ally_categorie WHERE ally_id = '$ally_id'");
		$db->no_query("DELETE FROM ally WHERE id = '$ally_id'");
		$result = $db->fetch("SELECT player_id FROM ally_user_list WHERE ally_id = '$ally_id'");
		while ($users = $result->fetch_array()) {
			$db->no_query("INSERT INTO `report`(`userid`, `type`, `booty`, `readed`) VALUES ('".$users['player_id']."', 'al_delete', '".$user['id']."', '0')");
			$db->no_query("UPDATE user SET report = '1' WHERE id = '".$users['player_id']."'");
		}
		$db->no_query("DELETE FROM ally_user_list WHERE ally_id = '$ally_id'");
		$no_error = true;
	}
	if(isset($_POST['welcome_u'])) {
		$db->no_query("UPDATE ally SET welcome = '".$_POST['welcome']."' WHERE id = '$ally_id'");
		$no_error = true;
	}
	if(isset($_POST['speichern'])) {
		$laenge = strlen($_POST['beschr']);
		if($laenge > "1000") {
			$error = "Sie duerfen leider hoechstens 1000 Zeichen verwenden!";
		} else {
			$db->no_query("UPDATE ally SET beschr = '".$_POST['beschr']."' WHERE id = '$ally_id'");
		}
	}
	if(isset($_POST['change_ally'])) {
		$ally_short = $_POST['ally_short'];
		$allyfrei = $db->query("SELECT id FROM ally WHERE ally_short = '$ally_short' AND id != '$ally_id'");
		$short_lange = strlen($ally_short);
		if($short_lange > "6") {
			$error = "Abkuerzung darf nur 6 Zeichen lang sein!";
		} elseif($allyfrei != "0") {
			$error = "Abkuerzung bereits vorhanden!";
		} else {
		$sql = "UPDATE ally SET ally_short = '$ally_short',ally_name = '".$_POST['ally_name']."', homepage = '".$_POST['homepage']."', board = '".$_POST['board']."'";
		if(!empty($_FILES['logo']['tmp_name']) and $_FILES['logo']['size'] <= 60000) {
			$typ = explode(".", $_FILES['logo']['name']);
			$picname = $ally_short.$user['id'].spezielstring().".".end($typ);
			move_uploaded_file($_FILES['logo']['tmp_name'], "upload/".$picname);
			$sql .= ", `logo` = '$picname'";
			$_POST['logouse'] = "aaaa";
		}
		if($_POST['logouse'] == "not") {
			$sql .= ", `logo` = ''";
		}
		$sql .= " WHERE id = '$ally_id'";
		$db->no_query($sql);
		$no_error = true;
	}

}
?>
<script>
function count(val) {
   document.getElementById('ausgabe').innerHTML = val.length + " Zeichen wurden eingegeben";
}
</script>
<form method="post" action="#" enctype="multipart/form-data">
<table class="vis" id="center"><tr><th colspan="2">Allgemeine Einstellungen</th></tr>
<tr><td>Allianzname &auml;ndern:</td><td><input type="text" name="ally_name" value="<? echo $ally['ally_name']; ?>"></td></tr>
<tr><td>Abk&uuml;rzung: (max. 6 Z) &auml;ndern:</td><td><input type="text" name="ally_short" value="<? echo $ally['ally_short']; ?>"></td></tr>
<tr><td>Allianzhomepage:</td><td><input type="text" name="homepage" value="<? echo $ally['homepage']; ?>"></td></tr>
<tr><td>eigenes Allianz-Forum:</td><td><input type="text" name="board" value="<? echo $ally['board']; ?>"></td></tr>
<tr><td>eigenes Allianz-Logo:</td><td><input type="file" name="logo" accept="image/*"></td></tr>
<tr><td>eigenes Allianz-Logo aktivieren:</td><td><input type="radio" name="logouse" value="true" <? if(!empty($ally['logo'])) { ?>checked<? } ?>>ja <input type="radio" name="logouse" value="not" <? if(empty($ally['logo'])) { ?>checked<? } ?>>nein</td></tr>
<tr><td colspan="2"><input type="submit" name="change_ally" value="&auml;ndern"></td></tr>
</table>
</form>
</p>
<p><b>Beschreibung</b><br />
H&ouml;chstens 1 000 Zeichen erlaubt!</p>
<form method="post" action="#">
<p>
<textarea cols="40" rows="12" name="beschr" id="beschr" onkeyup="count(this.value);"><?= $ally['beschr'] ?>
</textarea>
<script >var a = ""; showSmileys('beschr');</script>
<h6><script type="text/javascript">document.write("<div id='ausgabe'></div>");</script></h6><input type="submit" name="speichern" value="speichern"></p></form>

<p><b>Wilkommensnachricht</b><br />
Hierbei wird eine Nachricht an neue Mitglieder deines Allianzes gesendet!</p>
<form method="post" action="#">
<p>
<textarea cols="40" rows="12" id="answer" name="welcome"><?php echo $welcome; ?>
</textarea><br />
<script >var a = ""; showSmileys('answer');</script>
<input type="submit" name="welcome_u" value="speichern"></p></form>
<?php if ($level >= "3") { ?><form method="post" action="#"><input type="submit" onclick="return confirm('Allianz wirklich endgueltig AUFLOESEN?!');" value="Allianz aufl&ouml;sen?" name="delete_ally"></form>
<?php } } ?>
