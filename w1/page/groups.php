<b>Gruppen ändern:</b>
<?
$result = $db->fetch("SELECT * FROM `group` WHERE userid = '$id'");
while ($groups = $result->fetch_array()) {
	//Gruppennamen ändern
	if(isset($_POST['update_'.$groups['id']])) {
		$alreadyexists = $db->query("SELECT count(id) FROM `group` WHERE userid = '$id' AND `name` = '".$_POST['update_'.$groups['id']]."'");
		if($alreadyexists >= 1) {
			$error = "Gruppe mit dem selben Namen existiert bereits!";
		} else {
			$db->no_query("UPDATE `group` SET `name`='".$_POST['edit_name']."' WHERE id = '".$groups['id']."'");
			$no_error = true;
		}
	}
	if(isset($_POST['delete_'.$groups['id']])) {
		$db->no_query("UPDATE village SET `group` = '0' WHERE `group` = '".$groups['id']."'");
		$db->no_query("DELETE FROM `group` WHERE id = '".$groups['id']."'");
		$no_error = true;
	}
	if(isset($_POST['insert_'.$groups['id']])) {
		$db->no_query("UPDATE village SET `group` = '".$groups['id']."' WHERE id = '$hid'");
		$no_error = true;
	}
?>
<form method="post" action="#"><input type="text" name="edit_name" value="<? echo $groups['name']; ?>"><input type="submit" name="update_<? echo $groups['id']; ?>" value="UPDATE"><? if(empty($village['group'])) { ?><input type="submit" name="insert_<? echo $groups['id']; ?>" value="beitreten"><? } ?><input type="submit" name="delete_<? echo $groups['id']; ?>" onclick="return confirm('Gruppe wirklich auflösen?');"  value="löschen"></form>
<? } 
if(empty($village['group'])) {
	if(isset($_POST['new_group'])) {
		$alreadyexists = $db->query("SELECT count(id) FROM `group` WHERE userid = '$id' AND `name` = '".$_POST['name']."'");
		if($alreadyexists >= 1) {
			$error = "Gruppe mit dem selben Namen existiert bereits!";
		} else {
			$db->no_query("INSERT INTO `group`(`userid`, `name`) VALUES ('$id', '".$_POST['name']."')");
			$groupid = $db->query("SELECT id FROM `group` WHERE userid = '$id' AND name = '".$_POST['name']."'");
			$db->no_query("UPDATE `village` SET `group` = '$groupid' WHERE id = '$hid'");
			$no_error = true;
		}
	}
?><br />
<b>Gruppe erstellen:</b>
<form method="post" action="#"><input type="text" name="name"><input type="submit" name="new_group" value="neue Gruppe erstellen"></form>
<? 
} else { 
if(isset($_POST['logout'])) {
	$db->no_query("UPDATE `village` SET `group` = '0' WHERE id = '$hid'");
	$alreadyexists = $db->query("SELECT count(id) FROM `village` WHERE `group` = '".$village['group']."'");
	if($alreadyexists <= 0) {
		$db->no_query("DELETE FROM `group` WHERE id = '".$village['group']."'");
	}
	$no_error = "Gruppe erfolgreich verlassen!";
}
?>
<b>Aktuelle Gruppe verlassen:</b>
<form method="post" action="#"><input type="submit" name="logout" value="Gruppe verlassen"></form>
<? } ?>
