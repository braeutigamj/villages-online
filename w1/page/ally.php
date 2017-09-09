<div id="center">
<?
//Sollte User in einer Ally sein
	if($allyu['ally_id'] > 0) {
	$ally_id = $allyu['ally_id'];
	$ally = $db->assoc("SELECT * FROM ally WHERE id = '$ally_id'");
	$level = $allyu['level'];
?>
<p><b><? echo $ally['ally_name']; ?></b><br />
<a href="game.php?village=<?= $hid ?>&page=ally&show=overview">&Uuml;bersicht</a> - <a href="game.php?village=<?= $hid ?>&page=ally&show=sally">Profil</a> - <a href="game.php?village=<?= $hid ?>&page=ally&show=members">Mitglieder</a><? if ($level >= "1") { ?> - <a href="game.php?village=<?= $hid ?>&page=ally&show=invite">Einladen</a><? } if($level >= "2") { ?> - <a href="game.php?village=<?= $hid ?>&page=ally&show=settings">Einstellungen</a><? } ?> - <? if($ally['board'] != "") { ?><a href="<?= $config['url'] ?>?page=redir&url=<? echo $ally['board']; ?>" target="blank">Forum</a><? } else { ?><a href="game.php?village=<?= $hid ?>&page=ally&show=board">Forum</a><? } ?>
</p><hr />

<?
	if(isset($_GET['show'])) {
		$file2="page/ally/".$_GET['show'].".php";
		if (file_exists($file2)) {
			include $file2;
		} else {
			include "page/ally/overview.php";
		}
	} else {
	include "page/ally/overview.php";
	}
} else {
	if(isset($_POST['create_ally'])) {
		$ally_name = $_POST['ally_name'];
		$ally_short = $_POST['ally_short'];
		$short_lange = strlen($ally_short);
		if($village['gold'] < $config['ally']) {
			$error = "Du hast nicht genügend Gold (in diesem Dorf) um eine Allianz zu gründen!";
		} elseif($short_lange > "6") {
			$error = "Abkuerzung darf nur 6 Zeichen lang sein!";
		} else {
			$allyfrei = $db->query("SELECT id FROM ally WHERE ally_short = '$ally_short'");
			if($allyfrei != "0") {
				$error = "Abkuerzung bereits vorhanden!";
			} else {
				$db->no_query("UPDATE `village` SET gold = gold - 5 WHERE id = '$hid'");
				$db->no_query("INSERT INTO `ally` (`ally_name`, `ally_short`) VALUES ('$ally_name', '$ally_short');");
				$ally_id = $db->query("SELECT id FROM ally WHERE ally_short = '$ally_short'");
				$db->no_query("INSERT INTO `ally_user_list` (`ally_id`, `player_id`, `level`) VALUES ('$ally_id', '$id', '3');");
				$no_error = true;
			}
		}
	}
?>
<p><b>Allianz</b><br />
Um Mitglied bei einen Allianz zu werden musst du eine Enladung erhalten!</p>
<table class="vis"><tr>
<? if($db->exist("SELECT count(id) FROM ally_invite WHERE player_id = '$id'")) { ?>
<td>
<p><b>Einladungen:</b></p>
<?
$result = $db->fetch("SELECT * FROM ally_invite WHERE player_id = '$id'");	//Einladungen anzeigen lassen
if(isset($_POST['ablehnen'])){
	$which_invite = $_POST['which_invite'];
	$db->no_query("DELETE FROM ally_invite WHERE id='$which_invite'");
	$no_error = true;
}
if(isset($_POST['annehmen'])){
	$which_invite = $_POST['which_invite'];
	$ally_id_name = $db->query("SELECT ally_id FROM ally_invite WHERE id = '$which_invite'");	//Allianzesname des neuen Allianz
	$ally_id_your = $db->query("SELECT id FROM ally WHERE ally_name = '$ally_id_name'");	//Allianzesid des neuen Allianz
	$ally_message = $db->query("SELECT welcome FROM ally WHERE id = '$ally_id_your'");	//Wilkommensnachricht
	if($ally_message != "") {
		$db->no_query("INSERT INTO `message` (`userid`, `von_player`, `betreff`, `message`) VALUES ('$id', 'System', 'Herzlich Wilkommen in deinen neuen Allianz!', '$ally_message');");
	}
	$db->no_query("INSERT INTO `ally_user_list` (`ally_id`, `player_id`) VALUES ('$ally_id_your', '$id');");
	$db->no_query("DELETE FROM ally_invite WHERE id='$which_invite'");
	$no_error = true;
}
?>
<table><tr><th>Allianz</th><th>Annehmen</th><th>Ablehnen</th>
<?
while ($invites = $result->fetch_array()) {
	$ally_names = $db->query("SELECT ally_short FROM ally WHERE id = '".$invites['ally_id']."'");
?>
<form method="post" action="#"><tr><td><? echo $ally_names; ?><input type="text" value="<? echo $invites['id']; ?>" name="which_invite" style="display:none;"></td><td><input type="submit" name="annehmen" value="Anehmen"></td><td><input type="submit" name="ablehnen" value="Ablehnen"></td></tr></form>
<? } ?>
</table></td>
<? } ?>
<td>
<p><b>Eigenen Allianz gr&uuml;nden:</b><br />
<i>Achtung! Die Gründung einer Allianz kostet <?= $config['ally'] ?><img src="<?= $config['image_url'] ?>res/gold.png"> Siedlergold. Dies kann in der Siedlerstätte produziert werden.</i>
<form method="post" action="#">
<table><tr><td>
Allianzname: </td><td><input type="text" size="7" name="ally_name"></td></tr>
<tr><td>Abk&uuml;rzung: (maximal 6 Zeichen)</td><td> <input type="text" size="7" name="ally_short"></td></tr>
<tr><td colspan="2"><input type="submit" name="create_ally" value="Gr&uuml;nden"></td></tr></table>
</form>
</p></td><td><img src="<?= $config['image_url'] ?>pic/ally.png"></td>
</tr></table>
<? } ?>
</div>
