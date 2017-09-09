<?
$first = $db->query("SELECT name FROM user ORDER BY points DESC");
$secound = $db->query("SELECT `name` FROM user WHERE name != '$first' ORDER BY points DESC");
$third = $db->query("SELECT `name` FROM user WHERE name != '$first' AND name != '$secound' ORDER BY points DESC");
?>
<h1>Aktuelle Topspieler</h1>
<div style="float: right;">
Welt ändern <select name="info_world" onChange="changer(this.value);">
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
				</select>
</div>
<div class="hofe" id="center">1. Platz<br><img src="<?= $config['cdn'] ?>img/fame/gold.png"><b><?= $first; ?></b></div><br>
<table>
<tr><td><div class="hofe" id="left">2. Platz<br><img src="<?= $config['cdn'] ?>img/fame/silver.png"><b><?= $secound; ?></b></div><br></td><td>
<div class="hofe" id="right">3. Platz<br><img src="<?= $config['cdn'] ?>img/fame/bronze.png"><b><?= $third; ?></b></div><br></td></tr>
</table>
