<?
//Includiert alle Klassen
function __autoload($classname) {
    include "incl/class/".$classname.".php";
}
//Zeit in 00:00:00
function format_time($sek)
{
  // Wieviel Sekunden hat...
  $std = 3600;
  $min = 60;
  // $anzahl_std mit 0 initialisieren
  $anzahl_std = 0;
  // Scheife läuft sooft durch solange $sek grösser oder gleich $std ist
  while ($sek>=$std) {
    // von $sek wird 1x $std abgezogen
    $sek = $sek-$std;
    // $anzahl_std wird um 1 addiert
    $anzahl_std++;
  }
  // siehe oben
  $anzahl_min = 0;
  while ($sek>=$min) {
    $sek = $sek-$min;
    $anzahl_min++;
  }
  // Ausgabe als formatierter String
  RETURN sprintf ("%01s",$anzahl_std).":".sprintf ("%02s",$anzahl_min).":".sprintf ("%02s",$sek);
}
//Zeit in AM XX UM XX UHR
function format_date($stamp,$show_sek=false)
{
  // Heutes Datum
  $time = time();
  $yesterdayz = date("d", $time-172800);
  $yesterday = date("d", $time-86400);
  $today_day = date("d",$time );
  $tomorrow_day = date("d", $time+86400);
  $tomorrow_dayz = date("d", $time+172800);

  $return = "";
  if($yesterdayz == date("d", $stamp)) {
    $return = "vorgestern ";
  }
  elseif($yesterday == date("d", $stamp)) {
    $return = "gestern ";
  }
  elseif ($today_day==date("d", $stamp)) {
     $return = "heute ";
  }
  elseif ($tomorrow_day==date("d", $stamp)) {
    $return = "morgen ";
  }
  elseif ($tomorrow_dayz==date("d", $stamp)) {
    $return = "übermorgen ";
  }
  else
  {
    $return = "am " . date("d.m", $stamp) . " ";
  }

  if ($show_sek) {
    $return .= "um " . date("H:i:s",$stamp) . " Uhr";
  }
  else
  {
    $return .= "um " . date("H:i",$stamp) . " Uhr";
  }

  return $return;
}
/*
Einheiten rekrutieren
Benötigt: train.php
Engabe: Zeitbonus, Einheit, Anzahl, Baracke?
*/
function createtrain($wache_time, $what,$anzahl,$requesttype) {
global $hid,$config,$units, $db;
$zeit = time();
$unit_time = round(($units->time[$what] / $wache_time) / $config['speed']);
$unit_time = $unit_time + 1;
$last_time = $db->query("SELECT end_time FROM train WHERE village = '$hid' and type='$requesttype' ORDER BY id DESC LIMIT 1");
if($last_time < $zeit) { $last_time = $zeit; }
$time_end = ($unit_time * $anzahl) + $last_time;
$time_end = $time_end + 1;
$db->no_query("INSERT INTO `train`(`village`, `end_time`, `what`, `build_time`, `times`, `time_start`, `type`) VALUES ('$hid', '$time_end', '".$what."', '$unit_time', '".$anzahl."', '$last_time', '$requesttype')");
}
/*
Neues Dorf erstellen
Benötigt: start.php
Eingabe: startposition
*/
function showkoord($land) {
if($land == "z")
{
$land = rand(1,4);
if($land == "1")
{
$land = "no";
}
elseif($land == "2")
{
$land = "nw";
}
elseif($land == "3")
{
$land = "sw";
}
else
{
$land = "so";
}
}
if ($land == "no") {
$ykoord= mt_rand(0, 450) / 10;
$xkoord= mt_rand(0, 900) / 10;
  }
  if ($land == "nw") {
$xkoord= mt_rand(0, 900) / 10 + 180;
$ykoord= mt_rand(0, 450) / 10 + 270;
  }
  if ($land == "so") {
$ykoord= mt_rand(0, 450) / 10 + 90;
$xkoord= mt_rand(0, 900) / 10;
  }
  if ($land == "sw") {
$xkoord= mt_rand(0, 900) / 10 + 180;
$ykoord= mt_rand(0, 450) / 10 + 180;
  }
$koord['x'] = round($xkoord);
$koord['y'] = round($ykoord);
return $koord;
}

/* Berechnet die freien Händler
Benötigt: alle market.php
*/
function freehandler($hid, $village) {
global $db;
$village['handler'] = $village['market'] * 2;
$market_i = $db->assoc("SELECT * FROM market WHERE from_village = '$hid'");
$village['handler'] = $village['handler'] - $market_i['handler'];
$result= $db->fetch("SELECT * FROM market_angebot WHERE from_village = '$hid'");
while ($handeru = $result->fetch_array()) {
$village['handler'] -= $handeru['handler'];
}
return $village['handler'];
}
/* Berechnet die id des Dorfbesitzers
Benötigt: überall
*/
function givevilluid($hid) {
global $db;
$userid = $db->query("SELECT userid FROM village WHERE id = '$hid'");
return $userid;
}
function givemountuid($bid) {
global $db;
$userid = $db->query("SELECT userid FROM mountain WHERE id = '$hid'");
return $userid;
}
/* Sichert die Eingaben vom User, um vor Hackern zu schützen!
Benötigt: head.php
*/
function sercurity($get, $post, $request) {
  $arrary = array('<', '>', "'", '"',"DELETE","delete","DROP","drop","*","UPDATE","update","SELECT","select","INSERT","insert","INTO","into", "\\","\"","\'");
    foreach($post as $key => $val) {
    $post[$key] =str_replace($arrary, "", $val);
  }
  foreach($get as $key => $val) {
    $get[$key] = str_replace($arrary, "", $val);
  }
    foreach($_REQUEST as $key => $val) {
    $request[$key] = str_replace($arrary, "", $val);
  }
  return array("post"=>$post,"get"=>$get,"request"=>$request);
}

/* Zeigt Betreff zu type
Benötigt: report.php usw.
*/
function message_type($type) {
if($type == "market_send") {
$return = "Rohstoffe wurden versendet";
} elseif($type == "market_get") {
$return = "Rohstoffe erhalten";
} elseif($type == "invite") {
$return = "Du wurdest in eine Allianz eingeladen";
} elseif($type == "al_delete") {
$return = "Deine Allianz wurde aufgelöst";
} elseif($type == "drop_out") {
$return = "Du wurdest aus deiner Allianz entlassen!";
} elseif($type == "friend_delete") {
$return = "Du wurdest aus einer Freundesliste enfernt!";
} elseif($type == "friend_get")  {
$return = "Du hast eine Freundschaftsanfrage erhalten!";
} elseif($type == "friend_accept") {
$return = "Eine Freundschaftsanfrage wurde akzeptiert";
} elseif($type == "attack_lost") {
$return = "Der Angreifer hat verloren";
} elseif($type == "uv") {
$return = "Urlaubsvertrettung";
} else {
$return = "Der Angreifer hat gewonnen";
}
return $return;
}

/* Berechnet Anzahl aller Dörfer eines Spielers
Benötigt: ranking/user.php
*/
function getvillages($userid) {
global $db;
$villages = 0;
$result = $db->fetch("SELECT id FROM village WHERE userid = '$userid'");
while ($userv = $result->fetch_array()) {
$villages += 1;
}
return $villages;
}

/*Berechnet die Größe des Speichers
*/
function getmaxress($level) {
global $config;
$max_res = $level * 104.31 * ($config['speed'] / 0.5);
return $max_res;
}

/*Berechnet die Größe des Farm
*/
function getmaxfood($level) {
global $config;
return $config['max_food'][$level];
}
/* Macht support in normalen Einheiten zeugs
Benötigt: mountain.php
*/
function givemesupport($uniti) {
  global $units;
  $uniti = explode(",", $uniti);
  $i = 0;
  foreach($units->name as $item => $key) {
    $unit[$item] = $uniti[$i];
    $i++;
  }
  return $unit;
}
//Zuständig zur BB-Code Formation
function bbcode($bbcode) {
  global $config;
  $bbcode = nl2br ($bbcode);
  //smyles
  $bbcode=preg_replace("/\:(.*?)\:/si", "<img src='".$config['cdn']."smyle/\\1.gif' border='0'>", $bbcode);
  $bbcode=preg_replace("/\[b\](.*?)\[\/b\]/si", "<b>\\1</b>", $bbcode);
  $bbcode=preg_replace("/\[i\](.*?)\[\/i\]/si", "<i>\\1</i>", $bbcode);
  $bbcode=preg_replace("/\[s\](.*?)\[\/s\]/si", "<s>\\1</s>", $bbcode);
  $bbcode=preg_replace("/\[u\](.*?)\[\/u\]/si", "<u>\\1</u>", $bbcode);
  //Textposition
  $bbcode=preg_replace("/\[center\](.*?)\[\/center\]/si", "<center>\\1</center>", $bbcode);
  $bbcode=preg_replace("/\[right\](.*?)\[\/right\]/si", '<p style="text-align:right">\\1</p>', $bbcode);
  $bbcode=preg_replace("/\[left\](.*?)\[\/left\]/si", '<p style="text-align:left">\\1</p>', $bbcode);
  //Links
  $bbcode=preg_replace("/\[url\](.*?)\[\/url\]/si", '<a href="redir.php?url=\\1" target="_blank" border="0">\\1</a>', $bbcode);
  $bbcode=preg_replace("/\[code\](.*?)\[\/code\]/si", '<center>CODE:<div style="text-align:left;border:1px solid black;font-size:12px;color:green;background-color:#ececec;width:500px;padding:5px;">\\1</div></center>', $bbcode);
  $bbcode=preg_replace("/\[url=(.*?)\](.*?)\[\/url\]/si", '<a href="redir.php?url=\\1" target="_blank" border="0">\\2</a>', $bbcode);
  //Style
  $bbcode=preg_replace("/\[a href=(.*?)\](.*?)\[\/a\]/si", '<a href="redir.php?url=\\1" target="_blank" border="0">\\2</a>', $bbcode);
  $bbcode=preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", '<font color="\\1">zitiert von <i>\\2</i></font>', $bbcode);
  $bbcode=preg_replace("/\[qoute=(.*?)\](.*?)\[\/qoute\]/si", '<font color="">\\2<div id="date">Zitiert von \\1</div></font>', $bbcode);
  //Game
  $bbcode=preg_replace("/\[user\](.*?)\[\/user\]/si", "<a href='game.php?page=splayer&id=\\1'>\\1</a>", $bbcode);
  return $bbcode;
}

//Berechnet die perfekte Serverurl
function get_path($url) {
  global $config;
  $path = $config['w1']."game.php";
  if(isset($url['page'])) {
    $path .= "?page=".$url['page'];
  } else {
    $path .= "?page=overview";
  }
  if(isset($url['show'])) {
    $path .= "&show=".$url['show'];
  }
  if(isset($url['x'])) {
    $path .= "&x=".$url['x'];
  }
  if(isset($url['y'])) {
    $path .= "&y=".$url['y'];
  }
  if(isset($url['id'])) {
    $path.= "&id=".$url['id'];
  } else {
    $path .= "&id=1";
  }
  return $path;
}

//Zufällig generiter Zeichenstring
function spezielstring($length = "6") {
  $characters = "abcdefghijklmnopqrstuvwxyz";
  $string = "";
  for ($i = 0; $i < $length; $i++) {
                 $string .= $characters[mt_rand(0, strlen($characters)-1)];
  }
  return $string;
}

//Werbungsscript
function spotmaker($size = "468") {
  global $dblogin, $config;
  $return = false;
  $banner = $dblogin->assoc("SELECT * FROM `addtable` WHERE `size` = '$size' ORDER BY RAND() limit 0, 1");
  if($banner['id'] > 0) {
      if(!empty($banner['linkurl'])) {
        $bannerd = str_replace("?", "QUESTIONM", $banner['linkurl']);
        $bannerd = str_replace("&", "ANDST", $bannerd);
        $return .= "<a href='".$config['url']."?page=redir&url=".$bannerd."&id=".$banner['id']."' target='_blank'><img src='".$banner['picurl']."'></a>";
      } else {
        $return = $config['picurl'];
      }
  }
  return $return;
}
//Überprüft ob ein Dorf auf dieser Position steht
function checkplace($x, $y, $gid=false) {
  global $db;
  $id = $db->query("SELECT id FROM village WHERE x = '$x' AND y = '$y'");
  if($id > 0) {
    if($gid) {
      $return = $id;
    } else {
      $return = "village";
    }
  } else {
    $return = "land";
  }
  return $return;
}

//Schreibt ein Cookie
function cookie($variable, $wert = ".", $time = 13824298) {
  global $_COOKIE, $zeit;
  $time = $zeit + $time;
  setcookie($variable, $wert, $time);
  $_COOKIE[$variable] = $wert;
  return true;
}

//Koodiert eine Array
function kodiert($array) {
  $return = "";
  foreach($array as $key => $wert) {
    $return .= $key."!WERT!".$wert."!KEY!";
  }
  return $return;
}
//Dekoodiert eine Array
function dekodiert($array) {
  $keyuwert = explode("!KEY!", $array);
  $return = array();
  foreach($keyuwert as $wert) {
    $retp = explode("!WERT!", $wert);
    $return[$retp[0]] = $retp[1];
  }
  return $return;
}


//Berechnet den gewinner eines Kampfes
function make_fight($att, $def, $other) {
global $config, $units;
//Einheiten wieder namen geben
$att = explode(",", $att);
$def = explode(",", $def);
$i = 0;
foreach($units->name as $item => $key) {
  $att[$item] = $att[$i];
  $def[$item] = $def[$i];
  $i++;
}
//Nun Punkte generieren
$att_fus = 0;
$att_cav = 0;
$defPoints_Foot = 0;
$defPoints_Cav = 0;
foreach($units->name as $item => $key) {
  $other['tech']['att'][$item] = $other['tech']['att'][$item] / 0.24;  //<-- Schmiede
  $other['tech']['def'][$item] = $other['tech']['def'][$item] / 0.24;
  if($units->type[$item] == 2) {
    $att_cav += $att[$item] * $units->att[$item] * ($other['tech']['att'][$item] + 1);
  } else {
    $att_fus += $att[$item] * $units->att[$item] * ($other['tech']['att'][$item] + 1);
  }
  $defPoints_Foot += $def[$item] * $units->deff[$item] * ($other['tech']['def'][$item] + 1);
  $defPoints_Cav += $def[$item] * $units->deffcav[$item] * ($other['tech']['def'][$item] + 1);
}
$attPoints_Total = $att_fus + $att_cav;
//Off ausrechnen
if($attPoints_Total <= 0)
  {
    $attFoot_factor = 0;
    $attCav_factor = 0;
  }
  else
  {
    $attFoot_factor = ($att_fus+1) / $attPoints_Total;
    $attCav_factor = ($att_cav+1) / $attPoints_Total;
  }
  $attPoints_real = $attPoints_Total;
// Und nun die effektive Stärke der Offtruppen ausrechnen
  $attFoot = $attPoints_real * $attFoot_factor;
  $attCav = $attPoints_real * $attCav_factor;

  // Nun können wir erst die Deff-Faktoren ausrechnen
  $defPoints_Foot *= $attFoot_factor;
  $defPoints_Cav *= $attCav_factor;
  $defPoints_Total = $defPoints_Foot + $defPoints_Cav;

  if($defPoints_Total <= 0)
  {
    $defFoot_factor = 0;
    $defCav_factor = 0;
  }
  else
  {
    $defFoot_factor = $defPoints_Foot / $defPoints_Total;
    $defCav_factor = $defPoints_Cav / $defPoints_Total;
  }

  // Alle Faktoren mit einbeziehen
  $defPoints_real = $defPoints_Total;
  //Wall einberechnen
  $defPoints_real *= ($other['wall'] * $config['w_bonus'])+1;
  //Verteidigungsbonus einberechnen
  $defPoints_real *= ($other['miliz'] / 100) + 1;
  // Und die wirkliche Stärke der Deff ausrechnen
  $defFoot = $defPoints_real * $defFoot_factor;
  $defCav = $defPoints_real * $defCav_factor;
// Dann kämpfen wir doch mal
  // Gewinnt der Angreifer?
  if($attPoints_real > $defPoints_real)
  {
  $winner = "off";
  $def_lose = $def;
    $att_lost_total = 0;
    $att_lose = array();
    if($defFoot <= 0)
    {
      $att_lose_foot = 0;
    }
    else
    {
      $att_lose_foot = (($defFoot*pow($defFoot, 1/2))/($attFoot*pow($attFoot, 1/2)));
    }
    if($defCav <= 0)
    {
      $att_lose_cav = 0;
    }
    else
    {
      $att_lose_cav = (($defCav*pow($defCav, 1/2))/($attCav*pow($attCav, 1/2)));
    }
    foreach($units->name as $item => $key) {
      if($units->type[$item] == 2) {
        $att_lose[$item] = round($att[$item] * $att_lose_cav);
      } else {
        $att_lose[$item] = round($att[$item] * $att_lose_foot);
      }
    $att_lost_total += $att_lose[$item];
    }
  //Oder gewinnt der Verteidiger?
  } else {
    $winner = "deff";
    $att_lose = $att;
    $def_lost_total = 0;
    $def_lose = array();
    if($defPoints_real <= 0) {
      $def_lose_factor = 0;
    } else {
      $def_lose_factor = (($attPoints_real*pow($attPoints_real, 1/2))/($defPoints_real*pow($defPoints_real,1/2)));
    }
    foreach($units->name as $item => $key) {
      $def_lose[$item] = round($def[$item] * $def_lose_factor);
      $def_lost_total += $def_lose[$item];
      //Späher sollen immer LEBEN!
      if($att['spy'] > 0) {
        if($def['spy'] > 0) {
          $deff_spears_fac = ($def['spy'] / 3) * 2;
          if($att['spy'] < $deff_spears_fac) {
            $att_lose['spy'] = $att['spy'];
          } else {
            $att_spear_alive = $att['spy'] / 3;
            $att_lose['spy'] = floor($att['spy'] - $att_spear_alive);
          }
        } else {
          $att_lose['spy'] = 0;
        }
      }
    }
  //Ende kampf
  }

  //Bug Fixer :D
  foreach($att_lose as $key=>$value)
  {
    if($value > $att[$key])
    {
      $att_lose[$key] = $att[$key];
    }
  }
  foreach($def_lose as $key=>$value)
  {
    if($value > $def[$key])
    {
      $def_lose[$key] = $def[$key];
    }
  }
  return array("att"=>$att,"att_lose"=>$att_lose,"def"=>$def,"def_lose"=>$def_lose, "winner"=>$winner);
}
?>
