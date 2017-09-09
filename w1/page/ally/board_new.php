<?php
if(isset($_GET['category'])) {
	if(isset($_POST['create'])) {
		$db->no_query("INSERT INTO `ally_board_thread` (`player_id`, `ally_id`, `ally_categorie`, `thread_name`, `text`, `lastanswer`, `time`) VALUES ('$id', '$ally_id', '".$_GET['category']."', '".$_POST['title']."', '".$_POST['text']."', '$zeit', '$zeit')");
		$no_error = true;
		$return_error = "game.php?village=".$hid."&page=ally&show=board";
	}
	$category = $_GET['category'];
	$cates = $db->fetch("SELECT * FROM ally_categorie WHERE ally_id = '$ally_id'");	//Kategorien anzeigen
	echo "<p>";
	$i = 0;
	while ($catesanz = $cates->fetch_array()) {
		$i++;
	if($i > 1) {
		echo " - ";
	}
	if($catesanz['id'] == $category) {
		echo "<i>";
	}
?>
<a href="game.php?village=<?= $hid ?>&page=ally&show=board&category=<?php echo $catesanz['id']; ?>"><?php echo $catesanz['name']; ?></a>
<?php if($catesanz['id'] == $category) { echo "</i>"; } } ?></p><hr />
<form method="post" action="#">
Titel: <input type="text" name="title"><br />
Text: <textarea cols="39" rows="6" id="text" name="text"></textarea><br />
<script >var a = ''; showSmileys('text');</script>
<input type="submit" name="create" value="Thread erstellen">
</form>
<? } else { ?>
<a href="game.php?village=<?= $hid ?>&page=ally&show=board">Zurück zum Forum</a>
<? } ?>
