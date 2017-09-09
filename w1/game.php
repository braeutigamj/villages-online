<?
$zeit = time();
require_once "incl/function.php";
$building = new building;
$units = new units;
require_once "config.php";
$db = new db('w'.$config['id']);  //<- Datenbankverbindung
$reload = new reload;
$foot = new foot;
$session = new session;
if($config['tutorial'] > 0) {
  $tutorial = new tutorial;
}
require_once "incl/fight.php";
$id = $session->check("w".$config['id']);
if(!is_numeric($id)) {
  header("Location: ".$config['url']);
  exit;
}
//schützt das Script vor Angriffen über POST und GET!
$requests = sercurity($_GET, $_POST, $_REQUEST);
$_GET = $requests['get'];
$_POST = $requests['post'];
$_REQUEST = $requests['request'];
$server['path'] = get_path($_GET);
if(isset($_GET['village'])) {  //ändert das aktuelle Dorf
  $hid = $db->query("SELECT count(id) FROM village WHERE id = '".$_GET['village']."'");
  if($hid > 0) {
    $_SESSION['hid'] = $_GET['village'];
  }
}
if(isset($_GET['new_vil'])) {  //ändert das aktuelle Dorf
  $hid = $db->query("SELECT count(id) FROM village WHERE id = '".$_GET['new_vil']."'");
  if($hid > 0) {
    $_SESSION['hid'] = $_GET['new_vil'];
  }
}
//Load User informations!
if(!isset($_SESSION['hid'])) {
  $hid = $db->query("SELECT id FROM village WHERE userid = '$id' LIMIT 1");
  if($hid <= 0) {
    header("Location: start.php");
    exit;
  }
  $_SESSION['hid'] = $hid;
} else {
  $hid = $_SESSION['hid'];
}
$user = $db->assoc("SELECT * FROM user WHERE id = '$id'");
$village = $db->assoc("SELECT * FROM village WHERE id = '".$_SESSION['hid']."'");
if($village['userid'] != $user['id']) {
  unset($_SESSION['hid']);
  header("Location: game.php");
  exit;
}
$allyu = $db->assoc("SELECT * FROM ally_user_list WHERE player_id = '$id'");
$village['max_food'] = getmaxfood($village['farm']);
$village['max_res'] = getmaxress($village['storage']);
if(isset($_GET['spot'])) {
  if($_GET['spot'] == "close") {
    $_SESSION['spottime'] = $zeit + 900;
    $error = "Werbung wird 15 Minuten lang versteckt!";
  } elseif($_GET['spot'] == "right") {
    cookie("spotplace", "right");
  } elseif($_GET['spot'] == "top") {
    cookie("spotplace", "top");
  }
}
//Produziert der Rohstoffe! in Sekunden
foot::generateres($hid,$village);
if(isset($_GET['logout'])) {
  $session->destroy($id, "w".$config['id']);
  header("Location: ".$config['url']."index.php");
}
if(isset($_GET['tut']) and $config['tutorial'] > 0) {
  if($tutorial->check($_GET['tut']) == "no_error") {
    $no_error = true;
  }
}
if(!isset($_GET['page']) || empty($_GET['page'])) { $_GET['page'] = "overview"; }
$train = array("barracks", "stable", "garage", "settlerplace");
if(in_array($_GET['page'], $train)) {
  $_GET['page'] = "train";
}
header("Content-Type: text/html; charset=utf-8");
if(isset($_GET['chattype'])) {
  if($_GET['chattype'] == "ally") {
    cookie("chattype", "ally");
  } else {
    cookie("chattype", "shoutbox");
  }
}
$last_village = $db->query("SELECT id FROM village WHERE name < '".$village['name']."' AND userid = '$id' ORDER BY name DESC LIMIT 1");
$next_village = $db->query("SELECT id FROM village WHERE name > '".$village['name']."' AND userid = '$id' ORDER BY name ASC LIMIT 1");
?>
<!DOCTYPE html>
<head>
  <link href="<?= $config['cdn'] ?>style.css" rel="stylesheet" type="text/css" />
  <link href="<?= $config['cdn'] ?>js/messi.css" rel="stylesheet" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <script type="text/javascript" src="<?= $config['cdn'] ?>smyle/smiley.js"></script>
  <script type="text/javascript" src="<?= $config['cdn'] ?>js/jquery.js"></script>
  <script type="text/javascript" src="<?= $config['cdn'] ?>js/messi.js"></script>
  <script type="text/javascript" src="<?= $config['cdn'] ?>js/drop.js"></script>
  <script type="text/javascript" src="<?= $config['cdn'] ?>js/game.js"></script>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="author" content="Jonas Bräutigam">
  <meta name="description" content="<?= $config['description']; ?>">
  <meta name="keywords" content="<?= $config['keyword']; ?>">
  <title>.: <?= $config['name']." :. ".$village['name'] ?></title>
  <script>
    var cdn_url = "<?= $config['cdn'] ?>";
  </script>
</head>
<body>
<div class="villageinfo">
  <span style="float: left;"><a href="game.php?village=<?= $hid ?>&logout" style="color: yellow;">Ausloggen</a></span>
<div style="float: right; padding-right: 2em;">
<?
$attacks = $db->query("SELECT count(id) FROM `movement` WHERE `type` = 'attack' AND `to_user` = '$id'");
if($attacks > 0) { ?>
  <img src="<?= $config['cdn'] ?>img/stuff/alarm.gif"> <b><? echo $attacks."</b>"; } ?>
  <b>Rohstoffe:</b>
    <img src="<? echo $config['image_url']; ?>res/wood.png" width="16" id="<?= $village['wood_r'] * $village['wood_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=wood"><? echo floor($village['wood_r']); $loadres[] = $village['wood_r'] * $village['wood_r'] * 2.69 * $config['speed']; ?></a>
    <img src="<? echo $config['image_url']; ?>res/clay.png" width="16" id="<?= $village['clay_r'] * $village['clay_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=clay"><? echo floor($village['clay_r']); $loadres[] = $village['clay_r'] * $village['clay_r'] * 2.69 * $config['speed']; ?></a>
    <img src="<? echo $config['image_url']; ?>res/iron.png" width="16" id="<?= $village['iron_r'] * $village['iron_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=iron"><? echo floor($village['iron_r']); $loadres[] = $village['iron_r'] * $village['iron_r'] * 2.69 * $config['speed']; ?></a>
    <img src="<? echo $config['image_url']; ?>res/res.png" width="16" width="25px"><a href="game.php?village=<?= $hid ?>&page=storage"><? echo floor($village['max_res']); ?></a>
  <b>Bevölkerung:</b>
    <img src="<? echo $config['image_url']; ?>res/food.png" width="16" width="25px"><a  href="game.php?village=<?= $hid ?>&page=farm"><? echo floor($village['food'])."/".floor($village['max_food']); ?></a>
  <b>Dorf:</b>
  <a href="game.php?village=<?= $hid ?>&page=overview"><? echo $village['name']." (".$village['x']."|".$village['y']; ?>)</a>
  <? if($village['group'] > 0) { ?>
    <a href="javascript:tuwas();"><img src="<?= $config['cdn'] ?>img/down.png"></a><script>
      function tuwas() {
      new Messi('<? $result = $db->fetch("SELECT id,name,x,y FROM village WHERE userid = '$id' AND `group` = '".$village['group']."' ORDER BY name"); while ($villages = $result->fetch_array()) { ?><a href="game.php?village=<?= $hid ?>&page=overview&new_vil=<? echo $villages['id']; ?>"><? echo $villages['name']." (".$villages['x']."|".$villages['y']; ?>)<br /><? } ?>', {title: 'Dörfer in dieser Gruppe', width: '280px', titleClass: 'success', buttons: [{id: 0, label: 'Close', val: 'X'}]});
      }
    </script></a>
  <? } ?>
  <a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $village['id']; ?>"><img src="<? echo $config['image_url']; ?>stuff/info.png"></a>
<?
if($last_village > 0) {
  echo '<a href="'.$server['path'].'&new_vil='.$last_village.'"><img src="'.$config['cdn'].'img/links.png"></a> ';
}
if($next_village > 0) {
  echo ' <a href="'.$server['path'].'&new_vil='.$next_village.'"><img src="'.$config['cdn'].'img/rechts.png"></a>';
}
?></div>
</div><br />
<? if((!isset($_COOKIE['spotplace']) || $_COOKIE['spotplace'] != "right") and (!isset($_SESSION['spottime']) || $_SESSION['spottime'] < $zeit)) { ?>
<div id="center" class="addtop">
<div class="addclose"><a href="<?= $server['path'] ?>&spot=close">close</a> | <a href="<?= $server['path'] ?>&spot=right">verschieben</a></div>
<? echo spotmaker(); ?>
<div id="date">Selbst Werbung auf <?= $config['name'] ?>? <a href="game.php?village=<?= $hid ?>&page=mail&show=new&at=Admin&betreff=Werbung buchen&message=Möchtest du per View oder per Klick bezahlen? Preisvorstellung? Anzahl? Hier hast du Platz dafür!">Dann kontaktiere uns über den Support!</a></div>
</div>
<? }
if($config['tutorial'] > 0) {
  echo $tutorial->box($user['tut']);
}
?>
  <table class="head" id="inhalt">
    <tr>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=overview_v">Dörfer</a>
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=overview_v&show=building">Gebäude</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=overview_v&show=units">Einheiten</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=overview_v&show=moving">Truppen</a></td></tr>
  </table>
</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=report"> Berichte <? if($user['report'] == 1) { echo '<img src="'.$config['cdn'].'img/report.png">'; } ?></a><br />
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=report">Alle Berichte</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=report&show=attack">Angriffe</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=report&show=market">Marktplatz</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=report&show=ally">Allianz</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=report&show=friend">Freunde</a></td></tr>
  </table>

</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=mail"> Nachrichten <? if($user['message'] == 1) { echo '<img src="'.$config['cdn'].'img/message.png">'; } ?></a><br />
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=mail">Nachrichten lesen</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=mail&amp;show=new">Nachricht schreiben</a></td></tr>
  </table>
</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=ranking">Rangliste</a> (R <?= $db->query("SELECT count(id) FROM `user` WHERE `points` > '".$user['points']."'")+1 ?> | P <? echo $user['points']; ?>) <br />
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=ranking&amp;show=ally">Allianzen</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=ranking&amp;show=player">Spieler</a></td></tr>
  </table>
</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=ally">Allianz</a>
<? if($allyu['level'] >= 1) {
             echo '<table cellspacing="0" width="120">';
              echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=overview">Übersicht</a></td></tr>';
              echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=sally">Profil</a></td></tr>';
              echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=members">Mitglieder</a></td></tr>';
      if($allyu['level'] > 1) {
        echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=invite">Einladen</a></td></tr>';
                echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=settings">Einstellungen</a></td></tr>';
      }
      echo '<tr><td><a href="game.php?village='.$hid.'&page=ally&show=board" target="_blank"><img src="'.$config['image_url'].'/stuff/link.png" /></a> <a href="game.php?village='.$hid.'&page=ally&show=board">Forum</a></td></tr>';
            echo '</table>';
    }
?>
</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=settings">Einstellungen</a>
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=settings">Allgemein</a></td></tr>
  <tr><td><a href="<?= $config['url'] ?>index.php?page=setting" target="_blank">Weltübergreifend</a></td></tr>
  </table>
</td>
<td class="head_td"><a href="game.php?village=<?= $hid ?>&page=extra">Extras</a>
  <table cellspacing="0" width="120">
  <tr><td><a href="game.php?village=<?= $hid ?>&page=note">Notizblock</a></td></tr>
  <tr><td><a href="<?= $config['board'] ?>" target="_blank">Forum</a></td></tr>
  <tr><td><a href="game.php?village=<?= $hid ?>&page=mail&show=new&at=Admin&betreff=Supportanfrage&message=Bitte beschreibe dein Problem möglichst genau!">Support</a></td></tr>
  </table>
</td>
</tr></table>
<? if($user['extra_show'] == 0) { ?>
  <table class="head" style="width: 1%;">
    <tr>
      <td><a href="game.php?village=<?= $hid ?>&page=main" >Hauptgebäude</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=barracks" >Barracke</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=stable" >Stall</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=garage" >Werkstatt</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=settlerplace" >Siedlerstätte</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=smith" >Schmiede</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=place" >Dorfplatz</a></td>
      <td><a href="game.php?village=<?= $hid ?>&page=market" >Marktplatz</a></td>
</tr></table><? } ?>
<hr class="content">

<table class="body">
<? if(isset($_GET['mail'])) {
  echo "<tr><td id='center'><b>Vergiss nicht deine <i>Email</i> zu bestätigen! Sonst kannst du dich nicht mehr einloggen!</b></td></tr>";
} ?>
<tr><td>
<table>
<tr><td><a href="game.php?village=<?= $hid ?>&page=map"><img src="<? echo $config['image_url']; ?>stuff/map.png"></a>
<a href="game.php?village=<?= $hid ?>&page=overview"><? echo $village['name']." (".$village['x']."|".$village['y']; ?>)</a>
<? if($village['group'] > 0) { ?>
<a href="javascript:tuwas();"><img src="<?= $config['cdn'] ?>img/down.png"></a><script>
function tuwas() {
new Messi('<? $result = $db->fetch("SELECT id,name,x,y FROM village WHERE userid = '$id' AND `group` = '".$village['group']."' ORDER BY name"); while ($villages = $result->fetch_array()) { ?><a href="game.php?village=<?= $hid ?>&page=overview&new_vil=<? echo $villages['id']; ?>"><? echo $villages['name']." (".$villages['x']."|".$villages['y']; ?>)<br /><? } ?>', {title: 'Dörfer in dieser Gruppe', width: '280px', titleClass: 'success', buttons: [{id: 0, label: 'Close', val: 'X'}]});
}
</script></a> <? } ?>
<a href="game.php?village=<?= $hid ?>&page=smap&id=<? echo $village['id']; ?>"><img src="<? echo $config['image_url']; ?>stuff/info.png"></a>
<?
if($last_village > 0) {
  echo '<a href="'.$server['path'].'&new_vil='.$last_village.'"><img src="'.$config['cdn'].'img/links.png"></a> ';
}
if($next_village > 0) {
  echo ' <a href="'.$server['path'].'&new_vil='.$next_village.'"><img src="'.$config['cdn'].'img/rechts.png"></a>';
}
$attacks = $db->query("SELECT count(id) FROM `movement` WHERE `type` = 'attack' AND `to_village` = '$hid'");
if($attacks <= 0) {
$news = $dblogin->query("SELECT `message` FROM `news` ORDER BY id DESC LIMIT 1");
?>
</td><td width="50%"><b>News:</b> <marquee width="350px"><?= $news; ?></marquee></td>
<? } else { ?>
</td><td width="50%"><b><img src="<?= $config['cdn'] ?>img/stuff/alarm.gif">Achtung auf dieses Dorf kommen <u><i><?= $attacks; ?></i></u> Angriffe rein!<img src="<?= $config['cdn'] ?>img/stuff/alarm.gif"></b></td>
<? } ?>
</tr>
</table><hr />
</td></tr>
<tr><td>
<?
if(!$config['stop'] and $_GET['page'] == "start") {
  $_GET['page'] = "overview";
}
if(!$config['stop'] || in_array($_GET['page'], $config['allowed'])) {
  $file = "page/".$_GET['page'].".php";
  if (file_exists($file)) {
    include $file;
  } else {
    include "page/overview.php";
  }
} else {
  include "page/start.php";
}

if($user['admin'] > 1) {
?><br><hr /><a href="game.php?village=<?= $hid ?>&page=admin">Zum Adminpanel</a><? } ?>
<div id="date">Serverzeit: <? echo date("d.m.Y")." ".date("H:i:s"); ?> - <a href="<?= $config['url'] ?>?page=impressum" target="_blank">Impressum</a> - <?= $config['name'] ?> © 2012 - 20<? echo date("y"); ?></div>
</td></tr></table>
<?
  if(!isset($_COOKIE['chattype']) || $_COOKIE['chattype'] != "ally" || $allyu['ally_id'] < 1) {
    $result = $db->fetch("SELECT * FROM shoutbox ORDER BY id DESC LIMIT 0,4");
    if(isset($_POST['post_mes'])) {
      if(strlen($_POST['mess']) > 300) {
        $error = "Nachricht zu lang!";
      } elseif(strlen($_POST['mess']) < 5) {
        $error = "Nachricht muss mindestens 5 Zeichen enthalten";
      } else {
        $db->no_query("INSERT INTO `shoutbox`(`name`, `message`, `time`) VALUES ('".$user['name']."', '".$_POST['mess']."', '$zeit')");
        $no_error = true;
      }
    }
?>
  <div class="shoutbox">
  <b><a href='?page=shout'>Shoutbox öffnen</a><? if($allyu['ally_id'] > 0) { ?><br />Wechseln zum <a href='<?= $server["path"] ?>&chattype=ally'>Allianzchat</a><? } ?></b>
  <table class="vis" width="100%">
<?
  } else {
    $result = $db->fetch("SELECT * FROM ally_chat WHERE allyid = '".$allyu['ally_id']."' ORDER BY id DESC LIMIT 0,4");
    if(isset($_POST['post_mes'])) {
      if(strlen($_POST['mess']) > 300) {
        $error = "Nachricht zu lang!";
      } elseif(strlen($_POST['mess']) < 5) {
        $error = "Nachricht muss mindestens 5 Zeichen enthalten";
      } else {
        $db->no_query("INSERT INTO `ally_chat`(`name`, `message`, `time`, `allyid`) VALUES ('".$user['name']."', '".$_POST['mess']."', '$zeit', '".$allyu['ally_id']."')");
        $no_error = true;
      }
    }
?>
  <div class="shoutbox"><a href="game.php?village=<?= $hid ?>&logout" style="color: yellow;">Ausloggen</a><hr>
  <b>Shoutbox</b>
  <h6><a href='?page=shout'>Shoutbox öffnen</a> Wechseln zur <a href='<?= $server["path"] ?>&chattype=shoutbox'>Shoutbox</a></h6>
  <table class="vis" width="100%">
<?

  }
  while ($shout = $result->fetch_array()) {
    echo '<tr><td style="word-break: break-all;">'.bbcode($shout['message']).'</td></tr><tr><th><div id="date">';
    echo "<a href='game.php?village=<?= $hid ?>&page=splayer&id=".$shout['name']."'>".$shout['name']."</a> ".format_date($shout['time']);
    echo '</div></th></tr>';
  }
  echo '</table><br />';
  echo '<b>Neue Nachricht verfassen (max. 150 Zeichen)</b><form method="post" action="#">';
  echo '<textarea cols="75" rows="2" id="shoutboxga" onkeyup="count(this.value);" name="mess"></textarea>';
  echo "<script >var a = ''; showSmileys('shoutboxga');</script>";
  echo '<input type="submit" name="post_mes" value="absenden"></form>';
echo '</div>';
 if((isset($_COOKIE['spotplace']) and $_COOKIE['spotplace'] == "right") and (!isset($_SESSION['spottime']) || $_SESSION['spottime'] < $zeit)) { ?>
<div id="center" class="addright">
<div class="addclose"><a href="<?= $server['path'] ?>&spot=close">close</a> | <a href="<?= $server['path'] ?>&spot=top">verschieben</a></div>
<? echo spotmaker("160"); ?>
</div>
<? } /*
<div class="ressource">
<?
$attacks = $db->query("SELECT count(id) FROM `movement` WHERE `type` = 'attack' AND `to_user` = '$id'");
if($attacks > 0) { ?><img src="<?= $config['cdn'] ?>img/stuff/alarm.gif"><br><hr>
<img src="<?= $config['cdn'] ?>img/fight/attack.png"> <b><? echo $attacks."</b><hr>"; } ?>
      <b>Rohstoffe:</b>
      <img src="<? echo $config['image_url']; ?>res/wood.png" id="<?= $village['wood_r'] * $village['wood_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=wood"><? echo floor($village['wood_r']); $loadres[] = $village['wood_r'] * $village['wood_r'] * 2.69 * $config['speed']; ?></a><br>
      <img src="<? echo $config['image_url']; ?>res/clay.png" id="<?= $village['clay_r'] * $village['clay_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=clay"><? echo floor($village['clay_r']); $loadres[] = $village['clay_r'] * $village['clay_r'] * 2.69 * $config['speed']; ?></a><br>
      <img src="<? echo $config['image_url']; ?>res/iron.png" id="<?= $village['iron_r'] * $village['iron_r'] * 2.69 * $config['speed'] ?>" width="25px"><a href="game.php?village=<?= $hid ?>&page=iron"><? echo floor($village['iron_r']); $loadres[] = $village['iron_r'] * $village['iron_r'] * 2.69 * $config['speed']; ?></a><br>
      <img src="<? echo $config['image_url']; ?>res/res.png" width="25px"><a href="game.php?village=<?= $hid ?>&page=storage"><? echo floor($village['max_res']); ?></a>
      <b>Bevölkerung:</b>
      <img src="<? echo $config['image_url']; ?>res/food.png" width="25px"><a  href="game.php?village=<?= $hid ?>&page=farm"><? echo floor($village['food'])."/".floor($village['max_food']); ?></a>
</div>
<? */
if(isset($error)) {
?>
<script>
new Messi('<? echo $error; ?>', {title: 'Information', titleClass: 'anim warning', center: true});
</script>
<?
}
if(isset($return_error)) {
    echo '<meta http-equiv="refresh" content="0; URL='.$return_error.'">';
}
if(isset($no_error) and !isset($return_error)) {
  echo '<meta http-equiv="refresh" content="0; URL='.$server["path"].'">';
}
echo "<script> ";
if(isset($loadtime)) {
    foreach($loadtime as $wert) {
      echo "counter('".$wert."'); ";
    }
}
if(isset($loadres)) {
    foreach($loadres as $wert) {
      echo "generateres('".$wert."'); ";
    }
}
echo "</script>";
/* AUFTRÄGE AKTUALLISATOR */
//Checkt alle Bauaufträge
$foot->buildmaker($hid,$village);
//Checkt alle Rekrutierungen
$foot->rekruitwache($hid);
//Forscht in der Schmiede
$foot->forschemich($hid,$village);
//Berechnet die Händlerbewegungen
$foot->moveahandlertovil($hid);
$foot->moveahandler($hid);
//Updatet Zustimmung
$foot->update_agrement($hid, $village);
//Beendet Truppenbewegungen
$foot->dofightfrom($hid, $id, $village);
$foot->othervil($hid);
//aktualliesiert user
$foot->userup($village);
//Checkt ob Tutorialbedingungen erfüllt sind!
if($user['tut'] < 6 and $config['tutorial'] > 0) {
  $tutorial->check($user['tut']+1);
}
/* AUFTRÄGE AKTUALLISATOR ENDE */
?>
<br />
<br />
<footer>
  Linktipps:
  <a href="<?= $config['help'] ?>" target="_blank">Hilfe</a> -
  <a href="<?= $config['board'] ?>" target="_blank">Forum</a> -
  <a href="<?= $config['help'] ?>help.php?help=adspot" target="_blank">
    Mach Werbung für <?= $config['name'] ?>!
  </a>
  <div style="float:right">
    Community:
    <a href="game.php?village=<?= $hid ?>&page=splayer&id=<?= $user['id']; ?>">
      Spielerprofil
    </a> -
    <a href="game.php?village=<?= $hid ?>&page=friends">Freunde</a>
    &nbsp;&nbsp;&nbsp;
  </div>
</footer>
<script type="text/javascript">
<!--

SET_DHTML("tutorial", "name2", "nochEinLayer", "letztesBild");

//-->
</script>
<br /><br />
</body>
</html>
