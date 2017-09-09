<?
//GELD MUSS NOCH ABGEZOGEN WERDEN!
if(isset($_POST['own'])) {
	$need_handler = ceil($_POST['sell'] / 1000);
	$need_handler = $need_handler * $_POST['times'];
	if(!isset($_POST['sell_w']) || !isset($_POST['buy_w']) || $_POST['sell'] <= 0 || $_POST['buy'] <= 0 || $_POST['times'] <= 0) {
		$error = "Bitte fülle alle Felder aus!";
	} elseif($_POST['buy_w'] == $_POST['sell_w']) { $error = "Du kannst nicht das Kaufen, das du Verkaufen möchtest!";
	} elseif($need_handler > $village['handler']) { $error = "Du hast nicht genug Händler!";
	} else {
		$db->no_query("INSERT INTO `market_angebot`(`handler`, `from_village`, `sell`, `sell_w`, `buy`, `buy_w`, `anzahl`) VALUES ('$need_handler', '$hid', '".$_POST['sell']."', '".$_POST['sell_w']."', '".$_POST['buy']."', '".$_POST['buy_w']."', '".$_POST['times']."')");
		$no_error = true;
	}
}
?>
<b>Eigenes Angebot erstellen:</b>
<form method="post" action="#">
<table width="350px" class="vis">
<tr><td>Verkaufen:</td><td><input type="text" name="sell" value="0" size="3"></td><td>
<img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><input type="radio" value="wood" name="sell_w">
<img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"><input type="radio" value="clay" name="sell_w">
<img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"><input type="radio" value="iron" name="sell_w"></td></tr>
<tr><td>Kaufen:</td><td><input type="text" name="buy" value="0" size="3"></td><td>
<img src="<? echo $config['image_url']; ?>res/wood.png" width="25px"><input type="radio" value="wood" name="buy_w">
<img src="<? echo $config['image_url']; ?>res/clay.png" width="25px"><input type="radio" value="clay" name="buy_w">
<img src="<? echo $config['image_url']; ?>res/iron.png" width="25px"><input type="radio" value="iron" name="buy_w"></td></tr>
<tr><td>Anzahl:</td><td colspan="2"><input type="text" name="times" value="1" size="2"></td></tr>
<tr><td colspan="3"><input type="submit" name="own" value="erstellen"></td></tr>
</table></form>

<b>Deine Angebote:</b>
<form method="post" action="#">
<table class="vis"><tr><th>L&ouml;schen</th><th>Händler</th><th>Verkaufen</th><th>Kaufen</th><th>Anzahl</th></tr>
<?php
$result = $db->fetch("SELECT * FROM market_angebot WHERE from_village = '$hid'");
while ($zeile = $result->fetch_array())
{

if(isset($_POST['delete']))
{
$id = $zeile['id'];
$id = $_POST[$id];
$db->no_query("DELETE FROM market_angebot WHERE from_village = '$hid' AND id = '$id'");
$error = "Angebot wurde gelöscht!";
} ?>
<tr><td><input type="checkbox" name="<?php echo $zeile['id']; ?>" value="<?php echo $zeile['id']; ?>"></td>
<td><? echo $zeile['handler']; ?></td>
<td><img src="<? echo $config['image_url']; ?>res/<? echo $zeile['sell_w']; ?>.png" width="25px"><? echo $zeile['sell']; ?></td>
<td><img src="<? echo $config['image_url']; ?>res/<? echo $zeile['buy_w']; ?>.png" width="25px"><? echo $zeile['buy']; ?></td>
<td><? echo $zeile['anzahl']; ?></td>
</tr>
<?php } ?>
<tr><td colspan="5"><input type="submit" name="delete" value="l&ouml;schen"></td></tr>
</table>
</fom>
