<?
$zeit = time();
require_once "incl/function.php";
$isglobal = 1;
require_once "config.php";
//require_once "w1/config.php";
if(!empty($_COOKIE['info_world']) || isset($_POST['world'])) {
  if(isset($_POST['world'])) {
    $world = $_POST['world'];
  } else {
    $world = $_COOKIE['info_world'];
  }
} else {
  $world = 1;
}
require_once "w".$world."/config.php";
$db = new db("w".$world);
$dbinfo = $db;
$reload = new reload;
$session = new session;
ob_start();
header("Content-Type: text/html; charset=utf-8");
//Auto Login
$userid = $session->check();
$requests = sercurity($_GET, $_POST, $_REQUEST);
$_GET = $requests['get'];
$_POST = $requests['post'];
$_REQUEST = $requests['request'];

if(isset($_POST['login'])) {
  if($session->check() != false) {
    $dblogin->no_query("DELETE FROM `session` WHERE `client` = '".$_SERVER['REMOTE_ADDR']."'");
    session_destroy();
  }
  $password = md5($config['sec_pw'].$_POST['password'].$config['sec_pwz']);
  $userid = $dblogin->query("SELECT id FROM login WHERE name = '".@$_POST['user']."' AND password = '$password' AND activate = '0'");
  if($userid >= 1) {
    $user = $dblogin->assoc("SELECT * FROM `login` WHERE id = '$userid'");
    $player = $db->query("SELECT id FROM user WHERE global_id = '".$userid."'");
    if($player >= 1) {
      $session->create($userid, $cookie);
      $session->create($player, $cookie, "w".$_POST['world']);
      header("Location: ".$config["w".$_POST['world']]."game.php?page=start");
    } else {
      $db->no_query("INSERT INTO user (name, global_id, admin) VALUES ('".$user['name']."', '".$userid."', '".$user['admin']."')");
      $player = $db->query("SELECT id FROM user WHERE global_id = '".$userid."'");
      $session->create($userid, $cookie);
      $session->create($player, $cookie, "w".$_POST['world']);
      header("Location: ".$config["w".$_POST['world']]."game.php?page=start");
    }
  } else {
    $error = "Fehler! Zugangsdaten sind nicht korrekt bzw. Account wurde nicht aktiviert!";
  }
}

if(isset($_POST['logind'])) {
  if(isset($_POST['cookie'])) {
    $cookie = true;
  } else {
    $cookie = false;
  }
  $dbworld = new db("w".$_POST['world']);
  $player = $dbworld->query("SELECT id FROM `user` WHERE global_id = '$userid'");
  $session->create($player, $cookie, "w".$_POST['world']);
  header("Location: ".$config['w'.$_POST['world']]."game.php?page=start");
}
if(isset($_GET['logout'])) {
  session_destroy();
  $session->destroy($userid);
  header("Location: index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href='<?= $config['cdn'] ?>style.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="<?= $config['cdn'] ?>js/jquery.js" /></script>
<title>.: <?= $config['name'] ?> :.</title>
<meta name="author" content="Jonas Bräutigam">
<meta name="description" content="<?= $config['description']; ?>">
<meta name="keywords" content="<?= $config['keyword']; ?>">
<?
if(!isset($_GET['page']) || $_GET['page'] == "index") {
  $_GET['page'] = "overview";
}
if($_GET['page'] == "overview") {
  echo '<link rel="stylesheet" type="text/css" href="'.$config["cdn"].'js/gallerystyle.css" />';
  echo '<script type="text/javascript" src="'.$config["cdn"].'js/motiongallery.js" /></script>';
} ?>
<script type="text/javascript" src="<?= $config["cdn"] ?>js/style.js" /></script>
</head>
<body>
<br /><br /><? /*
<div class="login">
<h4>Login</h4>
<form method="post" action="#">
<? if($userid == false) { ?>
                 <input type="text" size="10" style="font-size:0.8em; text-align:center;" value="Username" class="main_menu" onFocus="if(this.value=='Username') this.value=''" name="user"><br />
               <input type="password" size="10" onFocus="if(this.value=='Passwort') this.value=''" style="font-size:0.8em; text-align:center;" class="main_menu" value="Passwort" name="password"><br />
<input type="checkbox" name="cookie">Login speichern?<br />
        <select name="world">
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
        </select><br />
<input type="submit" name="login" value="Login"><br />
<a href="index.php?page=pw" class="main_menu">Passwort vergessen?</a><br />
<? } else {
$user = $dblogin->query("SELECT `name` FROM login WHERE id='$userid'");
?>
Hallo <b><?= $user; ?></b>,<br>
log dich jetzt ein:<br>
        <select name="world">
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
<input type="submit" name="logind" value="Login"><br />
<h6>Du bist nicht <b><?= $user; ?></b>?<br>
<a href="?logout">Ausloggen.</a></h6>
<? } ?>
</form>
</div> */ ?>
<div class="index">
  <div id="scraper">
    <div id="head"><a href="index.php"><img src="<?= $config['cdn'] ?>img/pic/head.png" width="1000px" height="250px"></a></div> <br /><br />
    <div class="loginz">
<form method="post" action="#">
<? if($userid == false) { ?><b>Login:</b>
                 <input type="text" size="10" style="font-size:0.8em; text-align:center;" value="Username" class="main_menu" onFocus="if(this.value=='Username') this.value=''" name="user">
               <input type="password" size="10" onFocus="if(this.value=='Passwort') this.value=''" style="font-size:0.8em; text-align:center;" class="main_menu" value="Passwort" name="password">
<input type="checkbox" name="cookie">Login speichern?
        <select name="world">
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
<input type="submit" name="login" value="Login">
<a href="index.php?page=pw" class="main_menu">Passwort vergessen?</a><a href="fb.php" style="float: right; padding-right: 1.5px;"><img src="<?= $config['cdn'] ?>img/stuff/fblogin.png" /></a>
<? } else {
$user = $dblogin->query("SELECT `name` FROM login WHERE id='$userid'");
?>
Hallo <b><?= $user; ?></b>!
        <select name="world">
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
<input type="submit" name="logind" value="Login">
<h6>Du bist nicht <b><?= $user; ?></b>?
<a href="?logout">Ausloggen.</a></h6>
<? } ?>
</form>
    <ul class="menu">
      <li <? if($_GET['page'] == "overview") { echo 'class="active"'; } ?>><a href="index.php?page=index">Home</a></li>
      <li <? if($_GET['page'] == "register") { echo 'class="active"'; } ?>><a href="index.php?page=register">Registrierung</a></li>
      <li <? if($_GET['page'] == "rules") { echo 'class="active"'; } ?>><a href="index.php?page=rules">Regeln</a></li>
      <li <? if($_GET['page'] == "hall_of_fame") { echo 'class="active"'; } ?>><a href="index.php?page=hall_of_fame">HoF</a></li>
      <li <? if($_GET['page'] == "rounds") { echo 'class="active"'; } ?>><a href="index.php?page=rounds">Rundenplan</a></li>
      <li <? if($_GET['page'] == "rounds") { echo 'class="active"'; } ?>><a href="<?= $config['board'] ?>" target="_blank">Forum</a></li>
      <? /*<li <? if($_GET['page'] == "team") { echo 'class="active"'; } ?>><a href="index.php?page=team">Team</a></li>*/ ?>
      <li><a target="_blank" href="<?= $config['help'] ?>">Hilfe</a></li>
    </ul>
  </div><marquee width="100%"><?= $config['slogan']; ?></marquee></div>
<?
$file = "index/".$_GET['page'].".php";
if (file_exists($file)) {
  include $file;
} else {
  include "index/overview.php";
}
?>
<div id="date"><a href="index.php?page=activate">Account aktivieren</a> - Serverzeit: <? echo date("d.m.Y"); ?> <span id="zeit">a</span> - <a href="index.php?page=impressum">Impressum</a> - <?= $config['name'] ?> © 2012 - 20<? echo date("y"); ?></div>
<?
ob_end_flush();
if(isset($error)) {
?><script>
alert('<? echo $error; ?>');
</script>
<meta http-equiv="refresh" content="0; URL=index.php?page=<?= $_GET['page']; ?>">
<? } ?>
</div>
<script>
  zeiter();
</script>
<br /><br />
</body>
</html>
