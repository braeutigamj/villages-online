<script>
function openpic(url) {
document.getElementById('popup').innerHTML = "<img src='" + url + "' class='popup' id='center'>";
}
function closeitpic() {
document.getElementById('popup').innerHTML = "";
}
</script>
<p id="popup" onclick="closeitpic();"></p>
		<h1>Willkommen bei <?= $config['name'] ?>!</h1>
			<p>In diesem kostenlose, Echtzeit Multiplayer Mittelalter Browsergame beginnst du mit einem kleinen Dorf. Dieses baust du aus um deinen Machtbereich zu erweitern. Das Ziel ist es Alleinherscher oder Herscher mit deiner Allianz zu werden! </p>
<div class="worldinfo">
<table class="vis"><tr><th colspan="2">Weltinformationen 
				<select name="info_world"  onChange="changer(this.value);">
					<? $result = $dblogin->fetch("SELECT * FROM `worlds`");
					while($worlds = $result->fetch_array()) {
						echo '<option value="'.$worlds['id'].'"';
						if(empty($_COOKIE['info_world']) and !isset($setworld) and $worlds['enddate'] >= date("Y-m-d")) {
							echo ' selected';
							$setworld = true;
						} elseif(!empty($_COOKIE['info_world']) and $world == $worlds['id']) {
							echo ' selected';
						}
						echo '>Welt '.$worlds['id'].' </option>';
					} ?>
				</select></th></tr>
<tr><td>Startdatum</td><td><?= format_date($dblogin->query("SELECT `startdate` FROM `worlds` WHERE id = '$world'"), false, true) ?></td></tr>
<tr><td>Enddatum</td><td><?= format_date($dblogin->query("SELECT `enddate` FROM `worlds` WHERE id = '$world'"), false, true) ?></td></tr>
<tr><td>End-Uhrzeit</td><td><?= format_time($dblogin->query("SELECT `endtime` FROM `worlds` WHERE id = '$world'")) ?></td></tr>
<? if($dbinfo->exist("SELECT COUNT(id) FROM `user`")) { ?>
<tr><td>Registrierte Spieler</td><td><? echo $dbinfo->query("SELECT COUNT(id) FROM `user`"); ?></td></tr>
<tr><td>Spieler online</td><td><? $act = $zeit-"900"; echo $dbinfo->query("SELECT count(id) FROM `user` WHERE `last_activity` >= '$act'"); ?></td></tr>
<tr><td>aktive Spieler</td><td><? $act = $zeit-"1080000"; echo $dbinfo->query("SELECT count(id) FROM `user` WHERE `last_activity` >= '$act'"); ?></td></tr>
<? } ?>
<tr><td>Speed</td><td><?= $dblogin->query("SELECT `speed` FROM `worlds` WHERE id = '$world'"); ?></td></tr>
<tr><td>Wall Bonus</td><td><?= $dblogin->query("SELECT `w_bonus` FROM `worlds` WHERE id = '$world'"); ?></td></tr>
<tr><td>Mitgliederlimit pro Allianz</td><td><? if($dblogin->query("SELECT `max_ally` FROM `worlds` WHERE id = '$world'") > 0) { echo $dblogin->query("SELECT `max_ally` FROM `worlds` WHERE id = '$world'"); } else { echo "unbegrenzt"; } ?></td></tr>
<tr><td>Maximaler Rohstoffspeicher:</td><td><?= getmaxress(30); ?></td></tr>
<tr><td>Maximale Farm:</td><td><?= getmaxfood(30); ?></td></tr>
<? /*<tr><td colspan="2"><a href="forum" target="_blank">Mehr Informationen im Forum!</a></td></tr>*/ ?>
</table>
</div>
			<p><h3>Features:</h3>
			<ul>
				<li>Arbeiter um Rohstoffproduktion zu erhöhen</li>
				<li>mehrere verschiedene Welten</li>
				<li>tolle Community</li>
				<li>12 verschiedene Einheiten</li>
				<li>15 verschiedene Gebäude</li>
				<li>und vieles mehr...</li>
			</ul></p><a href="?page=register" id="register"><u>Jetzt kostenlos Anmelden!</u></a>
<p><h3>Screenshots:</h3>

<div id="motioncontainer" style="position:relative;overflow:hidden;">
<div id="motiongallery" style="position:absolute;left:0;top:0;white-space: nowrap;">
<nobr id="trueContainer">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic1.png');" src="<?= $config['image_url'] ?>info/pic1_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic2.png');" src="<?= $config['image_url'] ?>info/pic2_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic3.png');" src="<?= $config['image_url'] ?>info/pic3_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic4.png');" src="<?= $config['image_url'] ?>info/pic4_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic5.png');" src="<?= $config['image_url'] ?>info/pic5_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic6.png');" src="<?= $config['image_url'] ?>info/pic6_small.png" width="130px">
<img onclick="openpic('<?= $config['image_url'] ?>info/pic7.png');" src="<?= $config['image_url'] ?>info/pic7_small.png" width="130px">
</nobr>

</div>
</div>

</tr>
</table>
</p>
<p><table width="100%" class="vis">
<tr><th>News</th></tr>
<?
$result = $dblogin->fetch("SELECT * FROM news ORDER BY id DESC LIMIT 10");
while ($news = $result->fetch_array()) { ?>
<tr><td style="word-break: break-all;"><?= $news['message']; ?></tr><tr><th><div id="date"><?= format_date($news['time']); ?></div></th></tr>
<? } ?>
</table></p>
