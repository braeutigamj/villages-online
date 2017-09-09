<?php
if(isset($_POST['leave_ally']))
{
	$db->no_query("DELETE FROM ally_user_list WHERE player_id = '$id'");
	$reload->ally_points($ally['id']);
	$no_error = "Du hast den Allianz erfolgreich verlassen!";
}
if(isset($_POST['intern_an2']))
{
	$ally['intern_an'] = $_POST['intern_an'];
	$db->no_query("UPDATE ally SET `intern_an` = '".$ally['intern_an']."' WHERE id = '".$ally['id']."'");
}
?>
<table width="600px" class="vis" id="center"><tr><th>Interne Ank&uuml;ndigung</th></tr>
<tr><td><?php echo bbcode($ally['intern_an']); ?></td></tr></table>
<?php if($level > "1" and !isset($_POST['ankundi'])) { ?>
<form method="post" action="#"><input type="submit" value="&auml;ndern" name="ankundi"></form>
<?php } if(isset($_POST['ankundi']))
{?>
<form method="post" action="#">
<textarea cols="80" rows="25" id="answer" name="intern_an"><?php echo $ally['intern_an']; ?>
</textarea><br />
<script>var a = ""; showSmileys('answer');</script>
<input type="submit" value="UPDATE" name="intern_an2">
</form>
<?php } ?>
</p>
<form method="post" action="#"><input type="submit" onclick="return confirm('Allianz wirklich endgueltig verlassen?!');" value="Allianz verlassen?" name="leave_ally"></form>
