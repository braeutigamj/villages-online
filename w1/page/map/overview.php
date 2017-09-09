<?
//ÜBerarbeitetes MapScript
if(isset($_GET['width'])) {
  $width = $_GET['width'];
} else {
  $width = "15";
}
if($width % 2 == 0) {
  $width = $width+1;
}
if(isset($_GET['x'])) {
  $x = $_GET['x'];
} else {
  $x = $village['x'];
}
if(isset($_GET['y'])) {
  $y = $_GET['y'];
} else {
  $y = $village['y'];
}
$xstart = $x - (($width-1) / 2);
$xend = $x + (($width-1) / 2);
$ystart = $y - (($width-1) / 2);
$yend = $y + (($width-1) / 2);
?>
<div id="mapinfo">Fahre über ein Dorf um Informationen dafür anzuzeigen!</div>
<table style="line-height: 10px;" class="vis" border="0" cellspacing="0" cellpadding="0" style="border-spacing: 0px;">
              <tr>
                <th align="center" height="20" colspan="<?= $width+3 ?>"><a href="<? if($id==1) { ?>game.php?village=<?= $hid ?>&page=map&amp;x=<? echo $x; ?>&amp;y=<? echo $y+$width+1; } else { ?>big_map.php?x=<? echo $x; ?>&y=<? echo $y+$width+1; } ?>"><img src="<? echo $config['image_url']; ?>map/map_n.png" style=" position:relative;" /></a></th>

              </tr>
    <tr>
    <th height="20" rowspan="<?= $width+2 ?>"><a href="<? if($id==1) { ?>game.php?village=<?= $hid ?>&page=map&amp;x=<? echo $x-$width-1; ?>&amp;y=<? echo $y; } else { ?>big_map.php?x=<? echo $x-$width-1; ?>&y=<? echo $y; } ?>"><img src="<? echo $config['image_url']; ?>map/map_w.png" style=" position:relative;" /></a></th>
<? for($i = $ystart; $i <= $yend; $i++) { ?>
    <tr><td width="20" height="20" cellspacing="0" cellpadding="0" border="0" style="border-spacing: 0px;"><? echo $i; ?></td><? for($s = $xstart; $s <= $xend; $s++) { ?>
              <th cellspacing="0" cellpadding="0" border="0" style="border-spacing: 0px;"><? $id = checkplace($s, $i, true); if(is_numeric($id)) { echo '<a href="game.php?village=<?= $hid ?>&page=smap&id='.$id.'">'; } ?><img style=" position:relative;" <? if(is_numeric($id)) { echo 'onmouseover="mapinfo('.$id.')"'; } ?>width="50px" src="<?= $config['cdn'] ?>img/map/<? echo checkplace($s, $i); ?>.png" /><? if(is_numeric($id)) { echo "</a>"; } ?></th>
            <? }
if($i == $ystart) {
?>
<th width="20" rowspan="<?= $width+2 ?>"><a href="game.php?village=<?= $hid ?>&page=map&amp;x=<? echo $x+$width+1; ?>&amp;y=<? echo $y; ?>"><img src="<? echo $config['image_url']; ?>map/map_e.png" /></a></th>
<? } ?>
  </tr>
<? } ?>

<tr cellspacing="0" cellpadding="0"><td  width="20" height="20" cellspacing="0" cellpadding="0"></td><? for($s = $xstart; $s <= $xend; $s++) { ?>
              <td><?= $s ?></td>
            <? } ?>
</tr>
<tr>
                <th align="center" height="20" colspan="<?= $width+2 ?>"><a href="game.php?village=<?= $hid ?>&page=map&amp;x=<? echo $x; ?>&amp;y=<? echo $y-$width-1; ?>"><img src="<? echo $config['image_url']; ?>map/map_s.png" style="z-index:1; position:relative;" /></a></th>

              </tr>
</table>
<table><tr><td>
<form method="post" action="#"><table class="vis">
<tr><th colspan="2">Karte zentrieren</th></tr>
<tr><td>X: </td><td><input type="text" name="x" value="<?php echo $x; ?>" size="3" /></td></tr>
<tr><td>Y: </td><td><input type="text" name="y" value="<?php echo $y; ?>" size="3" /></td></tr>
<tr><td colspan="2"><input type="submit" value="&raquo; OK &laquo;" style="font-size: 10pt;" /></td></tr>
</table></form>
</td><td>
<table class="vis"><tr><th colspan="2">Dörfer Statistiken</th>
                </tr>
                <tr>
            <th>Alle Dörfer:</th><td><? $a = $db->query("SELECT COUNT(id) FROM `village`");
                echo $a; ?></td>
                </tr>
           <th>Alle Freie Dörfer:</th><td><? $a = $db->query("SELECT COUNT(id) FROM `village` WHERE `userid` = '-1'");
                echo $a; ?></td>
                <tr>
            <th>Dörfer von Spieler:</th><td><? $a = $db->query("SELECT COUNT(id) FROM `village` WHERE `userid` > '-1'");
                echo $a; ?></td>
                </tr>
                <tr>
                <th>Deine Dörfer:</th><td><? echo $db->query("SELECT count(id) FROM `village` WHERE `userid` = '".$user['id']."'"); ?></td>
            </tr></table>
</td></tr></table>
