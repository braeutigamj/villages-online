<?php
if(isset($_POST['speichern'])) {
	$laenge = strlen($_POST['notes']);
	if($laenge > "2500") {
		$error = "Sie dürfen leider höchstens 2 500 Zeichen verwenden!";
		$_POST['notes'] = substr($_POST['notes'], 0, 2500);
	}
	$db->no_query("UPDATE user SET notes = '".$_POST['notes']."' WHERE id = '$id'");
	$no_error = true;
}
?>
<script type='text/javascript'>
function count(val) {
   document.getElementById('ausgabe').innerHTML = val.length + " Zeichen wurden eingegeben";
}
</script>
<center>
<p><b>Notizblock</b><br />
Hier kannst du deine Notizen aufschreiben. Du darfst maximal 2.000 Zeichen verwenden!</p>
<form method="post" action="#">
<p>
<textarea cols="80" rows="25" name="notes" onkeyup="count(this.value);"><?php echo $user['notes']; ?>
</textarea>
<h6><script type="text/javascript">document.write("<div id='ausgabe'></div>");</script></h6>
</p>
<p><input type="submit" name="speichern" value="speichern"></p>
</form>
</center>
